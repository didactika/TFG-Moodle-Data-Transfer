<?php

/**
 * Services definition
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright 2024 ADSDR-FUNIBER Scepter Team <accion.docente@ct.uneatlantico.es>
 * @author Eduardo Estrada (e2rd0) <eduardo.estrada@ct.uneatlantico.es>
 * @author Franklin López
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

    'local_data_transfer_question_bank' => [
        'classname' => local_data_transfer\services\external_question_bank::class,
        'description' => 'Get questions from question bank',
        'type' => 'read',
        'ajax' => true,
    ],
];
