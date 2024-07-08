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
        'classname'   => 'local_data_transfer\services\external_course',
        'methodname'  => 'get_course_schema',
        'description' => 'Get course schema',
        'type'        => 'read',
        'ajax'        => true,
    ],
    'local_data_transfer_get_mods' => [
        'classname'   => 'local_data_transfer\services\external_mods',
        'methodname'  => 'get_mods_schema',
        'description' => 'Get mods schema',
        'type'        => 'read',
        'ajax'        => true,
    ],
];
