<?php

/**
 * Core course class
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 namespace local_data_transfer\export\schema;

/**
 * Course class
 * 
 * This class represents a course in Moodle
 * Handles the course data to be exported/imported
 */
class Course
{
    private $DB;
    public int $courseid;
    public ?Header $course_header = null;
    public ?Content $course_content = null;
    public ?Groups $course_groups = null;
    public ?Groupings $course_groupings = null;
    public bool $include_header;
    public bool $include_content;
    public bool $include_groups;
    public bool $include_groupings;

    /**
     * Constructor
     * 
     * @param int $courseid Course ID, if 0, no existing course is included
     * @param bool $include_header if true, include the course header
     * @param bool $include_content if true, include the course content 
     * @param bool $include_groups if true, include the course groups
     * @param bool $include_groupings if true, include the course groupings
     */
    public function __construct(
        int $courseid,
        bool $include_header = true,
        bool $include_content = true,
        bool $include_groups = true,
        bool $include_groupings = true
    ) {
        global $DB;
        $this->DB = $DB;
        $this->courseid = $courseid;
        $this->include_header = $include_header;
        $this->include_content = $include_content;
        $this->include_groups = $include_groups;
        $this->include_groupings = $include_groupings;
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
