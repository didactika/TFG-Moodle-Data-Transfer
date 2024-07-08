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
use local_data_transfer\export\schema\Section;

class Exporter
{

    private Course $course;

    /**
     * Get a course schema
     * 
     * @param int $courseid The course ID
     * @param bool $header Include header
     * @param bool $content Include content
     * @param bool $groups Include groups
     * @param bool $groupings Include groupings
     * @param array $opt Options
     */
    public function get_course_schema(int $courseid, bool $header, bool $content, bool $groups, bool $groupings, $opt)
    {
        $this->course = new Course(
            $courseid,
            $header,
            $content,
            $groups,
            $groupings
        );
        return $this->course->get_schema($opt);
    }

    /**
     * Get a section mods schema
     * 
     * @param int $sectionid The section ID
     */
    public function get_mods_schema(int $sectionid)
    {
        $section = new Section($sectionid);
        return $section->get_schema(true);
    }

}
