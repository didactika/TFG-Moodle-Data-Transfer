<?php

/**
 * Services definition
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\schema\course;

use stdClass;
use Exception;

/**
 * Retrieve and store course header data
 * 
 * CourseHeader class
 */
class Header
{
    public stdClass $schema;
    private int $courseid;
    private $DB;

    /**
     * Constructor
     * 
     * @param int $courseid
     */
    public function __construct(int $courseid)
    {
        global $DB;
        $this->DB = $DB;
        $this->courseid = $courseid;
        $this->schema = new stdClass();
        $this->set_headers();
        $this->set_headers_data();
    }

    /**
     * Set the course header data
     * 
     * This function creates headers in the schema object
     * if in the future we need to add new headers we can do it here
     * at the moment we only have the general headers.
     * 
     * @return void
     */
    private function set_headers(): void
    {
        $this->schema->general = new stdClass();
    }

    /**
     * Set the course header data in the schema object
     * 
     * If in the future we need to add new headers we must add them here
     * 
     * @return void
     */
    private function set_headers_data(): void
    {
        $this->fill_header_general();
    }

    /**
     * Fill the general header data in the schema object
     * 
     * Note: Check the JSON schema prototype to see the structure
     * 
     * @return void
     */
    private function fill_header_general(): void
    {

        $course = $this->DB->get_record('course', ['id' => $this->courseid], 'category, fullname, shortname, idnumber');
        if (!$course) {
            throw new Exception('Course not found');
        }

        $this->schema->general->category = $course->category;
        $this->schema->general->fullname = $course->fullname;
        $this->schema->general->shortname = $course->shortname;
        $this->schema->general->idnumber = $course->idnumber;
    }

    /**
     * Get the course header schema in JSON format
     * 
     * @return string JSON schema
     */
    public function get_json_schema(): string
    {
        return json_encode($this->schema);
    }
}
