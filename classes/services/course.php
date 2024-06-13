<?php

/**
 * Webservices definition
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_data_transfer\services;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . './../../vendor/autoload.php');

use local_data_transfer\schema\course\Course;

class external_course extends external_api
{
    /**
     * Returns the description of the external function parameters
     *
     * @return external_function_parameters The external function parameters
     */
    public static function get_course_schema_parameters()
    {
        return new external_function_parameters(
            [
                'courseid' => new external_value(PARAM_INT, 'course ID'),
                'includes' => new external_single_structure(
                    [
                        'header' => new external_value(PARAM_BOOL, 'include header', VALUE_OPTIONAL),
                        'content' => new external_value(PARAM_BOOL, 'include content', VALUE_OPTIONAL),
                        'groups' => new external_value(PARAM_BOOL, 'include groups', VALUE_OPTIONAL),
                        'groupings' => new external_value(PARAM_BOOL, 'include groupings', VALUE_OPTIONAL),
                    ]
                )
            ]
        );
    }

    /**
     * Get a course schema
     *
     * @param int $courseid The course ID
     * @param array $includes The sections to include
     * @return array The result of the operation
     */
    public static function get_course_schema($courseid, $includes)
    {
        $params = self::validate_parameters(self::get_course_schema_parameters(), ['courseid' => $courseid, 'includes' => $includes]);

        $course = new Course(
            $params['courseid'], 
            $params['includes']['header'], 
            $params['includes']['content'], 
            $params['includes']['groups'], 
            $params['includes']['groupings']
        );

        $opt = [
            'include_mods' => false
        ];

        return $course->get_schema($opt);
    }

    /**
     * Returns the description of the external function returns
     *
     * @return external_single_structure The external function returns
     */
    public static function get_course_schema_returns()
    {
        return new external_single_structure(
            [
                'courseid' => new external_value(PARAM_INT, 'course ID'),
                'header' => new external_single_structure(
                    [
                        'general' => new external_single_structure(
                            [
                                'category' => new external_value(PARAM_INT, 'category'),
                                'fullname' => new external_value(PARAM_TEXT, 'fullname'),
                                'shortname' => new external_value(PARAM_TEXT, 'shortname'),
                                'idnumber' => new external_value(PARAM_TEXT, 'idnumber'),
                            ],
                            'general',
                            VALUE_OPTIONAL
                        ),
                    ],
                    'header',
                    VALUE_OPTIONAL
                ),
                'content' => new external_single_structure(
                    [
                        'sections' => new external_multiple_structure(
                            new external_single_structure(
                                [
                                    'id' => new external_value(PARAM_INT, 'id'),
                                    'section' => new external_value(PARAM_INT, 'section'),
                                    'name' => new external_value(PARAM_TEXT, 'name'),
                                    'visible' => new external_value(PARAM_INT, 'visible'),
                                    'availability' => new external_value(PARAM_TEXT, 'availability'),
                                ]
                            )
                        ),
                    ],
                    'content',
                    VALUE_OPTIONAL
                ),
                'groups' => new external_multiple_structure(
                    new external_single_structure(
                        [
                            'id' => new external_value(PARAM_INT, 'group ID'),
                            'name' => new external_value(PARAM_TEXT, 'group name'),
                            'idnumber' => new external_value(PARAM_TEXT, 'group idnumber'),
                            'description' => new external_value(PARAM_RAW, 'group description'),
                        ]
                    ),
                    'groups', VALUE_OPTIONAL
                ),
                'groupings' => new external_multiple_structure(
                    new external_single_structure(
                        [
                            'id' => new external_value(PARAM_INT, 'grouping ID'),
                            'name' => new external_value(PARAM_TEXT, 'grouping name'),
                            'description' => new external_value(PARAM_RAW, 'grouping description'),
                            'groups' => new external_multiple_structure(
                                new external_value(PARAM_INT, 'group ID')
                            ),
                        ]
                    ),
                    'groupings', VALUE_OPTIONAL
                ),
            ]
        );
    }
}
