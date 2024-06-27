<?php

/**
 * Tasks definition
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$tasks = [
    [
        'classname' => 'local_data_transfer\tasks\EventsHandler',
        'blocking' => 0,
        'minute' => 0,
        'hour' => 0,
        'day' => '*', 
        'month' => '*',
        'dayofweek' => 0, 
    ],
    [
        'classname' => 'local_data_transfer\tasks\PendingCommands',
        'blocking' => 0,
        'minute' => 0,
        'hour' => 0,
        'day' => '*', 
        'month' => '*',
        'dayofweek' => 0, 
    ],
];

