<?php 
/**
 * Core course class
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\Core; 

defined('MOODLE_INTERNAL') || die();

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

    public $course_header;
    public $course_content;
    public array $groups;
    public array $groupings;
    
}

