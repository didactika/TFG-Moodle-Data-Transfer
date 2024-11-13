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
 * Webservices definition
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright   2024 ADSDR-FUNIBER Scepter Team <accion.docente@ct.uneatlantico.es>
 * @author      Eduardo Estrada (e2rd0) <eduardo.estrada@ct.uneatlantico.es>
 * @author      Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\services;

defined('MOODLE_INTERNAL') || die();

use external_api;
use external_function_parameters;
use external_value;
use external_single_structure;
use external_multiple_structure;
use local_data_transfer\export\Exporter;
use local_data_transfer\services\SchemaUtils;

class external_course extends external_api
{
    /**
     * Returns the description of the external function parameters.
     *
     * @return external_function_parameters The external function parameters.
     */
    public static function get_course_schema_parameters()
    {
        return new external_function_parameters(
            [
                'courseid' => new external_value(PARAM_INT, 'Course ID'),
                'includes' => new external_single_structure(
                    [
                        'header' => new external_value(PARAM_BOOL, 'Include course header', VALUE_DEFAULT, 0),
                        'content' => new external_value(PARAM_BOOL, 'Include course content', VALUE_DEFAULT, 0),
                        'groups' => new external_value(PARAM_BOOL, 'Include groups', VALUE_DEFAULT, 0),
                        'groupings' => new external_value(PARAM_BOOL, 'Include groupings', VALUE_DEFAULT, 0),
                        'questionbank' => new external_value(PARAM_BOOL, 'Include question bank', VALUE_DEFAULT, 0)
                    ]
                )
            ]
        );
    }

    /**
     * Get a course schema.
     *
     * @param int $courseid The course ID.
     * @param array $includes The sections to include.
     * @return array The result of the operation.
     */
    public static function get_course_schema($courseid, $includes)
    {
        $params = self::validate_parameters(
            self::get_course_schema_parameters(),
            ['courseid' => $courseid, 'includes' => $includes]
        );

        $opt = [
            'include_mods' => false
        ];

        $exp = new Exporter();
        return $exp->get_course_schema(
            $courseid,
            $params['includes']['header'],
            $params['includes']['content'],
            $params['includes']['groups'],
            $params['includes']['groupings'],
            $params['includes']['questionbank'],
            $opt
        );
    }

    /**
     * Returns the description of the external function returns.
     *
     * @param int $depth The dynamic depth for the question bank structure.
     * @return external_single_structure The external function returns.
     */
    public static function get_course_schema_returns()
    {
        // Pass the dynamically set depth to build the course schema structure.
        return self::build_course_schema_structure();
    }

    /**
     * Builds and returns the course schema structure with specified depth.
     *
     * @param int $depth Depth for question bank structure.
     * @return external_single_structure
     */
    private static function build_course_schema_structure(): external_single_structure
    {
        return new external_single_structure(
            [
                'courseid' => new external_value(PARAM_INT, 'Course ID'),
                'header' => new external_single_structure(
                    [
                        'general' => new external_single_structure(
                            [
                                'category' => new external_value(PARAM_INT, 'Course category ID'),
                                'fullname' => new external_value(PARAM_TEXT, 'Full name of the course'),
                                'shortname' => new external_value(PARAM_TEXT, 'Short name of the course'),
                                'idnumber' => new external_value(PARAM_TEXT, 'Course ID number'),
                            ],
                            'General course information',
                            VALUE_OPTIONAL
                        ),
                    ],
                    'Course header information',
                    VALUE_OPTIONAL
                ),
                'content' => new external_single_structure(
                    [
                        'sections' => new external_multiple_structure(
                            SchemaUtils::get_section_structure(false)
                        ),
                    ],
                    'Course content sections',
                    VALUE_OPTIONAL
                ),
                'groups' => new external_multiple_structure(
                    new external_single_structure(
                        [
                            'id' => new external_value(PARAM_INT, 'Group ID'),
                            'name' => new external_value(PARAM_TEXT, 'Group name'),
                            'idnumber' => new external_value(PARAM_TEXT, 'Group ID number'),
                            'description' => new external_value(PARAM_RAW, 'Group description'),
                        ]
                    ),
                    'Course groups',
                    VALUE_OPTIONAL
                ),
                'groupings' => new external_multiple_structure(
                    new external_single_structure(
                        [
                            'id' => new external_value(PARAM_INT, 'Grouping ID'),
                            'idnumber' => new external_value(PARAM_TEXT, 'Grouping ID number'),
                            'name' => new external_value(PARAM_TEXT, 'Grouping name'),
                            'description' => new external_value(PARAM_RAW, 'Grouping description'),
                            'groups' => new external_multiple_structure(
                                new external_value(PARAM_INT, 'Group ID')
                            ),
                        ]
                    ),
                    'Course groupings',
                    VALUE_OPTIONAL
                ),
                // Use SchemaUtils to get the question bank structure with dynamic depth
                'questionbank' => SchemaUtils::get_basic_course_structure(5, false),
            ]
        );
    }
}
