<?php

/**
 * Services definition
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\schema;

use stdClass;

/**
 * Retrieve and store course header data
 * 
 * CourseHeader class
 */
class CourseHeader
{

    public $schema;

    private $courseid;

    private $DB;

    /**
     * Constructor
     * 
     * @param object $course
     */
    public function __construct($courseid, $DB)
    {
        $this->DB = $DB;
        $this->courseid = $courseid;
        $this->schema = new stdClass();
        $this->set_headers();
        $this->set_headers_data();
    }

    /**
     * Set the course header data
     * 
     * This function create headers in the schema object
     * if in the future we need to add new headers we can do it here
     * at the moment we only have the general headers.
     * 
     * It should be a function to populate headers with data
     * 
     * 
     * @return void
     */
    private function set_headers()
    {
        return $this->schema->general = new stdClass();
    }

    /**
     * Set the course header data in the schema object
     * 
     * If in the future we need to add new headers we must add them here
     * 
     * @return void
     */
    private function set_headers_data()
    {
        $this->fill_header_general();
    }

    /**
     * Fill the general header data in the schema object
     * 
     * Note: Check the json schema prototype to see the structure
     * 
     * @return void
     */
    private function fill_header_general()
    {
        if(!$course = $this->DB->get_record('course', array('id' => $this->courseid), 'category, fullname, shortname, idnumber')){
            throw new \Exception('Course not found');
        }
        $this->schema->general->category = $course->category;
        $this->schema->general->fullname = $course->fullname;
        $this->schema->general->shortname = $course->shortname;
        $this->schema->general->idnumber = $course->idnumber;
        return;
    }

    public function get_course_header()
    {
        return $this->schema;
    }
}
