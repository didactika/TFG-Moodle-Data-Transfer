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

/**
 * Class implementing WS local_data_transfer_question_bank
 *
 * @package    local_data_transfer
 * @copyright 2024 ADSDR-FUNIBER Scepter Team <accion.docente@ct.uneatlantico.es>
 * @author Eduardo Estrada (e2rd0) <eduardo.estrada@ct.uneatlantico.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\services;

use external_function_parameters;
use external_single_structure;
use external_multiple_structure;
use external_api;
use external_value;
use local_data_transfer\export\schema\Question;
use local_data_transfer\export\schema\QuestionBank;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/externallib.php');

/**
 * Implementation of web service local_data_transfer_question_bank
 *
 * @package    local_data_transfer
 */
class external_question_bank extends external_api
{

    /**
     * Describes the parameters for local_data_transfer_question_bank
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters
    {
        return new external_function_parameters([
            'courseid' => new external_value(PARAM_INT, 'Course ID'),
            'depth' => new external_value(PARAM_INT, 'Depth of subcategory recursion', VALUE_DEFAULT, 3)
        ]);
    }

    /**
     * Implementation of web service local_data_transfer_question_bank
     *
     * @param int $courseid
     * @param int $depth
     */
    public static function execute($courseid, $depth = 3)
    {
        // Parameter validation.
        $params = self::validate_parameters(
            self::execute_parameters(),
            ['courseid' => $courseid, 'depth' => $depth]
        );

        // Validate context.
        $context = \context_system::instance();
        self::validate_context($context);

        $qb = new QuestionBank($params['courseid']);
        return $qb->export_course_questions($params['depth']);
    }

    /**
     * Describe the return structure for local_data_transfer_question_bank
     *
     * @return external_single_structure
     */
    public static function execute_returns(): external_single_structure
    {
        // Define question structure.
        $question_structure = new external_single_structure([
            'id' => new external_value(PARAM_INT, 'Question ID'),
            'name' => new external_value(PARAM_TEXT, 'Question name'),
            'questiontext' => new external_value(PARAM_RAW, 'Question text with format'),
            'qtype' => new external_value(PARAM_TEXT, 'Type of question'),
            'defaultmark' => new external_value(PARAM_FLOAT, 'Default mark for the question'),
            'answers' => new external_multiple_structure(
                new external_single_structure([
                    'id' => new external_value(PARAM_INT, 'Answer ID'),
                    'text' => new external_value(PARAM_RAW, 'Answer text'),
                    'fraction' => new external_value(PARAM_FLOAT, 'Fraction for this answer'),
                    'feedback' => new external_value(PARAM_RAW, 'Feedback for this answer')
                ])
            ),
            'hints' => new external_multiple_structure(
                new external_single_structure([
                    'id' => new external_value(PARAM_INT, 'Hint ID'),
                    'text' => new external_value(PARAM_RAW, 'Hint text')
                ])
            )
        ]);

        // Recursively define the category structure up to the specified depth.
        $depth = self::get_depth_parameter(); // Assuming this function retrieves the requested depth
        $category_structure = self::build_category_structure($depth, $question_structure);

        return new external_single_structure([
            'course' => new external_single_structure([
                'id' => new external_value(PARAM_INT, 'Course ID'),
                'fullname' => new external_value(PARAM_TEXT, 'Full name of the course'),
                'shortname' => new external_value(PARAM_TEXT, 'Short name of the course'),
                'categories' => new external_multiple_structure($category_structure, 'Top-level categories in the course')
            ])
        ]);
    }

    /**
     * Recursively builds the category structure to the specified depth.
     *
     * @param int $depth
     * @param external_single_structure $question_structure
     * @return external_single_structure
     */
    private static function build_category_structure($depth, $question_structure)
    {
        // Base case: if depth is zero, return a category structure without subcategories.
        if ($depth === 0) {
            return new external_single_structure([
                'id' => new external_value(PARAM_INT, 'Category ID'),
                'name' => new external_value(PARAM_TEXT, 'Category name'),
                'info' => new external_value(PARAM_RAW, 'Category description/info'),
                'questions' => new external_multiple_structure($question_structure, 'Questions in this category')
            ]);
        }

        // Recursive case: define subcategories and reduce depth.
        $subcategory_structure = self::build_category_structure($depth - 1, $question_structure);

        return new external_single_structure([
            'id' => new external_value(PARAM_INT, 'Category ID'),
            'name' => new external_value(PARAM_TEXT, 'Category name'),
            'info' => new external_value(PARAM_RAW, 'Category description/info'),
            'questions' => new external_multiple_structure($question_structure, 'Questions in this category'),
            'subcategories' => new external_multiple_structure($subcategory_structure, 'List of subcategories within this category')
        ]);
    }

    /**
     * Retrieves the depth parameter, defaulting to a safe level if not provided.
     *
     * @return int
     */
    private static function get_depth_parameter(): int
    {
        global $PAGE;

        $depth = optional_param('depth', 3, PARAM_INT);
        return max(0, min($depth, 5));
    }
}
