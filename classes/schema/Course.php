<?php 
/**
 * Core course class
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\schema;

/**
 * Course class
 * 
 * This class represents a course in Moodle
 * Handles the course data to be exported/imported
 * 
 * @since       1.0.0
 * @package     local_data_transfer
 * @category    core
 */
class Course{

    public $courseid;
    public CourseHeader $course_header;
    public $course_content;
    public array $groups;
    public array $groupings;
    private $DB;

    /**
     * Constructor
     * 
     * @param object $course
     */
    public function __construct($courseid = 0, $DB){
        $this->DB = $DB;
        $this->courseid = $courseid;
        if($courseid){
            $this->set_course_header();
            return;
        }
    }

    public function set_course_header(){
        $this->course_header = new CourseHeader($this->courseid, $this->DB);
    }

    



}

