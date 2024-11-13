<?php

/**
 * Webservices definition
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright  2024 ADSDR-FUNIBER Scepter Team <accion.docente@ct.uneatlantico.es>
 * @author     Eduardo Estrada (e2rd0) <eduardo.estrada@ct.uneatlantico.es>
 * @author     Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\services;

defined('MOODLE_INTERNAL') || die();

use external_api;
use external_function_parameters;
use external_value;
use external_single_structure;
use local_data_transfer\export\Exporter;

class external_mods extends external_api
{
    /**
     * Returns the description of the external function parameters
     *
     * @return external_function_parameters The external function parameters
     */
    public static function get_mods_schema_parameters()
    {
        return new external_function_parameters(
            [
                'sectionid' => new external_value(PARAM_INT, 'Section ID')
            ]
        );
    }

    /**
     * Get a section mods schema
     *
     * @param int $sectionid The section ID
     * @param array $includes The sections to include
     * @return array The result of the operation
     */
    public static function get_mods_schema($sectionid)
    {
        $params = self::validate_parameters(self::get_mods_schema_parameters(), ['sectionid' => $sectionid]);

        $exp = new Exporter();
        return $exp->get_mods_schema($params['sectionid']);
    }

/**
 * Returns the description of the external function returns
 *
 * @return external_single_structure The external function returns
 */
public static function get_mods_schema_returns()
{
    return SchemaUtils::get_section_structure(true);
}

}
