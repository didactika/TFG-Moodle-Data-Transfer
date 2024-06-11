<?php

/**
 * Services definition
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

$functions = [
    'local_data_transfer_get_course' => [
        'classname'   => 'external_course',
        'methodname'  => 'get_course_schema',
        'classpath'   => 'local/data_transfer/classes/webservices/course.php',
        'description' => 'Get course schema',
        'type'        => 'read',
        'ajax'        => true,
    ],
];
