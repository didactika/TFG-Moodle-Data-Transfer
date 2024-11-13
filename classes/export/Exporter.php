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

namespace local_data_transfer\export;

use local_data_transfer\export\schema\Course;
use local_data_transfer\export\schema\Section;

/**
 * Exporter class
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright 2024 ADSDR-FUNIBER Scepter Team <accion.docente@ct.uneatlantico.es>
 * @author Eduardo Estrada (e2rd0) <eduardo.estrada@ct.uneatlantico.es>
 * @author Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

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
     * @param bool $questionBank Include question bank
     * @param array $opt Options
     */
    public function get_course_schema(int $courseid, bool $header, bool $content, bool $groups, bool $groupings, bool $questionBank, $opt)
    {
        $this->course = new Course(
            $courseid,
            $header,
            $content,
            $groups,
            $groupings,
            $questionBank
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
