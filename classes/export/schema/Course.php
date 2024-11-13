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

/**
 * Core course class
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright 2024 ADSDR-FUNIBER Scepter Team <accion.docente@ct.uneatlantico.es>
 * @author Eduardo Estrada (e2rd0) <eduardo.estrada@ct.uneatlantico.es>
 * @author Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class Course
{
    private $DB;
    public int $courseid;
    public ?Header $course_header = null;
    public ?Content $course_content = null;
    public ?Groups $course_groups = null;
    public ?Groupings $course_groupings = null;

    public ?QuestionBank $questionBank = null;

    public $include_questionBank = null;
    public bool $include_header;
    public bool $include_content;
    public bool $include_groups;
    public bool $include_groupings;
    public bool $include_question_bank;

    /**
     * Constructor
     * 
     * @param int $courseid Course ID, if 0, no existing course is included
     * @param bool $include_header if true, include the course header
     * @param bool $include_content if true, include the course content 
     * @param bool $include_groups if true, include the course groups
     * @param bool $include_groupings if true, include the course groupings
     * @param bool $include_question_bank if true, include the course question bank
     */
    public function __construct(
        int $courseid,
        bool $include_header = true,
        bool $include_content = true,
        bool $include_groups = true,
        bool $include_groupings = true,
        bool $include_question_bank = true
    ) {
        global $DB;
        $this->DB = $DB;
        $this->courseid = $courseid;
        $this->include_header = $include_header;
        $this->include_content = $include_content;
        $this->include_groups = $include_groups;
        $this->include_groupings = $include_groupings;
        $this->include_question_bank = $include_question_bank;
        $this->set_course_data();
    }

    /**
     * Set the course data
     * 
     * This function sets the course data 
     * INCLUDES 
     * - Header 
     * - Content
     * - Groups
     * - Groupings
     */
    private function set_course_data(): void
    {
        if ($this->verify_if_course_exist() === false) {
            throw new \Exception('Course does not exist');
        }
        if ($this->include_header) {
            $this->set_header();
        }
        if ($this->include_content) {
            $this->set_content();
        }
        if ($this->include_groups) {
            $this->set_groups();
        }
        if ($this->include_groupings) {
            $this->set_course_groupings();
        }
        if ($this->include_question_bank) {
            $this->set_question_bank();
        }
    }

    /**
     * This function sets the course header
     * 
     * @return void
     */
    public function set_header(): void
    {
        $this->course_header = new Header($this->courseid);
    }

    /**
     * This function sets the course content
     * 
     * @return void
     */
    public function set_content(): void
    {
        $this->course_content = new Content($this->courseid);
    }

    /**
     * This function sets the course groups
     * 
     * @return void
     */
    public function set_groups(): void
    {
        $this->course_groups = new Groups($this->courseid);
    }

    /**
     * This function sets the course groupings
     * 
     * @return void
     */
    public function set_course_groupings(): void
    {
        $this->course_groupings = new Groupings($this->courseid);
    }

    /**
     * This function sets the course question bank
     * 
     * @return void
     */

    public function set_question_bank(): void
    {
        $this->questionBank = new QuestionBank($this->courseid);
    }

    /**
     * Get the course data schema
     * 
     * @param array $opt Options to include in the schema
     * @return array
     */
    public function get_schema(array $opt): array
    {
        list('include_mods' => $include_mods) = $opt;

        $course = [
            'courseid' => $this->courseid,
        ];

        if ($this->include_header && $this->course_header !== null) {
            $course['header'] = $this->course_header->get_schema();
        }
        if ($this->include_content && $this->course_content !== null) {
            $course['content'] = $this->course_content->get_schema($include_mods);
        }
        if ($this->include_groups && $this->course_groups !== null) {
            $course['groups'] = $this->course_groups->get_groups();
        }
        if ($this->include_groupings && $this->course_groupings !== null) {
            $course['groupings'] = $this->course_groupings->get_groupings();
        }

        if ($this->include_question_bank && $this->questionBank !== null) {
            $course['questionbank'] = $this->questionBank->export_course_questions(5, false);
        }

        return $course;
    }

    /**
     * Verify if the course exists
     * 
     * @return bool
     */
    private function verify_if_course_exist(): bool
    {
        return $this->DB->record_exists('course', ['id' => $this->courseid]);
    }
}
