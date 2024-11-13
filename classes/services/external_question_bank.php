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
 * Class implementing WS local_data_transfer_question_bank
 *
 * @package    local_data_transfer
 * @copyright  2024 ADSDR-FUNIBER Scepter Team <accion.docente@ct.uneatlantico.es>
 * @author     Eduardo Estrada (e2rd0) <eduardo.estrada@ct.uneatlantico.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\services;

use external_function_parameters;
use external_single_structure;
use external_api;
use external_value;
use local_data_transfer\export\schema\QuestionBank;
use local_data_transfer\services\SchemaUtils;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/externallib.php');

/**
 * Implementation of web service local_data_transfer_question_bank
 *
 * @package    local_data_transfer
 */
class external_question_bank extends external_api
{

    /**
     * Describes the parameters for local_data_transfer_question_bank
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters
    {
        return new external_function_parameters([
            'courseid' => new external_value(PARAM_INT, 'Course ID'),
            'depth' => new external_value(PARAM_INT, 'Depth of subcategory recursion', VALUE_DEFAULT, 1)
        ]);
    }

    /**
     * Implementation of web service local_data_transfer_question_bank
     *
     * @param int $courseid
     * @param int $depth
     */
    public static function execute($courseid, $depth = 1)
    {
        // Parameter validation.
        $params = self::validate_parameters(
            self::execute_parameters(),
            ['courseid' => $courseid, 'depth' => $depth]
        );

        // Validate context.
        $context = \context_system::instance();
        self::validate_context($context);

        $qb = new QuestionBank($params['courseid']);
        return $qb->export_course_questions($params['depth']);
    }

    /**
     * Describe the return structure for local_data_transfer_question_bank
     *
     * @return external_single_structure
     */
    public static function execute_returns(): external_single_structure
    {
        return SchemaUtils::get_basic_course_structure(self::get_depth_parameter());
    }


    /**
     * Retrieves the depth parameter, defaulting to a safe level if not provided.
     *
     * @return int
     */
    private static function get_depth_parameter(): int
    {
        $depth = optional_param('depth', 1, PARAM_INT);
        return max(0, min($depth, 5));
    }
}
