<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace local_data_transfer\export\schema;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/question/engine/bank.php');
require_once($CFG->dirroot . '/question/editlib.php');

/**
 * Question bank class
 *
 * @package    local_data_transfer
 * @copyright 2024 ADSDR-FUNIBER Scepter Team <accion.docente@ct.uneatlantico.es>
 * @author Eduardo Estrada (e2rd0) <eduardo.estrada@ct.uneatlantico.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class QuestionBank
{
    private $DB;
    private int $courseid;

    public function __construct(int $courseid)
    {
        global $DB;
        $this->courseid = $courseid;
        $this->DB = $DB;
    }

    /**
     * Exports all questions from a course's question bank, organized by categories and subcategories.
     *
     * @param int $depth Maximum depth for recursion of subcategories.
     * @return array The hierarchical data structure of categories, subcategories, and questions.
     * @throws \moodle_exception if the course or context is invalid.
     */
    public function export_course_questions($depth = 3, $includeCourseInfo = true)
    {
        global $PAGE;

        $PAGE->set_context(\context_system::instance());

        $context = \context_course::instance($this->courseid, IGNORE_MISSING);
        if (!$context) {
            throw new \moodle_exception('invalidcourseid', 'error', '', $this->courseid);
        }

        $course = $this->DB->get_record('course', ['id' => $this->courseid], 'id, fullname, shortname', MUST_EXIST);
        $result = $includeCourseInfo ? [
            'course' => [
                'id' => $course->id,
                'fullname' => $course->fullname,
                'shortname' => $course->shortname,
                'categories' => []
            ]
        ] : [
            'categories' => []
        ];

        // Fetch top-level categories in the course context
        $top_categories = $this->DB->get_records('question_categories', ['contextid' => $context->id, 'parent' => 0]);

        if (empty($top_categories)) {
            return $result;
        }

        foreach ($top_categories as $top_category) {
            if (empty($top_category->contextid) || !$this->DB->record_exists('context', ['id' => $top_category->contextid])) {
                throw new \moodle_exception('invalidcontext', 'error', '', $top_category->contextid);
            }

            $category_context = \context::instance_by_id($top_category->contextid);

            if (!has_capability('moodle/question:viewall', $category_context)) {
                throw new \moodle_exception('nopermissions', 'error', '', 'moodle/question:viewall');
            }

            // Pass the depth to the export_category function
            $exported_category = self::export_category($top_category, $depth);
            if($includeCourseInfo) {
                $result['course']['categories'][] = $exported_category;
            } else {
                $result['categories'][] = $exported_category;
            }
        }

        return $result;
    }

    /**
     * Recursively exports a category and its subcategories, including questions.
     *
     * @param object $category The category object.
     * @param int $depth Maximum depth for recursion. If 0, no subcategories will be included.
     * @return array The structured data for the category.
     */
    private function export_category($category, $depth)
    {
        $categorydata = [
            'id' => $category->id,
            'name' => format_string($category->name),
            'info' => format_text($category->info, FORMAT_HTML),
            'questions' => [],
            'subcategories' => []
        ];

        // Fetch questions in this category
        $question_entries = $this->DB->get_records_sql("
            SELECT q.id, q.name, q.questiontext, q.qtype, q.defaultmark, q.questiontextformat 
            FROM {question} q
            JOIN {question_versions} qv ON qv.questionid = q.id
            JOIN {question_bank_entries} qbe ON qbe.id = qv.questionbankentryid
            WHERE qbe.questioncategoryid = :categoryid
        ", ['categoryid' => $category->id]);

        if (!empty($question_entries)) {
            foreach ($question_entries as $question) {
                $categorydata['questions'][] = self::export_question($question);
            }
        }

        // Decrement depth with each recursive call, stopping if depth is zero
        if ($depth > 0) {
            $subcategories = $this->DB->get_records('question_categories', ['parent' => $category->id]);
            foreach ($subcategories as $subcategory) {
                $categorydata['subcategories'][] = self::export_category($subcategory, $depth - 1);
            }
        }

        return $categorydata;
    }

    /**
     * Exports a single question with its details.
     *
     * @param object $question The question object.
     * @return array The structured data for the question.
     */
    private function export_question($question)
    {

        $questiondata = [
            'id' => $question->id,
            'name' => format_string($question->name),
            'questiontext' => format_text($question->questiontext, $question->questiontextformat),
            'qtype' => $question->qtype,
            'defaultmark' => $question->defaultmark,
            'answers' => [],
            'hints' => []
        ];

        if (in_array($question->qtype, ['multichoice', 'truefalse', 'shortanswer'])) {
            $answers = $this->DB->get_records('question_answers', ['question' => $question->id]);
            foreach ($answers as $answer) {
                $questiondata['answers'][] = [
                    'id' => $answer->id,
                    'text' => format_text($answer->answer, FORMAT_HTML),
                    'fraction' => $answer->fraction,
                    'feedback' => format_text($answer->feedback, FORMAT_HTML)
                ];
            }
        }

        $hints = $this->DB->get_records('question_hints', ['questionid' => $question->id]);
        foreach ($hints as $hint) {
            $questiondata['hints'][] = [
                'id' => $hint->id,
                'text' => format_text($hint->hint, FORMAT_HTML)
            ];
        }

        return $questiondata;
    }
}
