<?php

/**
 * Exporter class
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\export;

use local_data_transfer\export\schema\Course;

class Exporter {

    private Course $course;
    
    public function __construct(int $courseid, bool $header, bool $content, bool $groups, bool $groupings)
    {
        $this->course = new Course(
            $courseid,
            $header,
            $content,
            $groups,
            $groupings
        );
        
    }

    public function get_course_schema($opt) 
    {
        return $this->course->get_schema($opt);
    }
}