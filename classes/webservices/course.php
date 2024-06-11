<?php 
/**
 * Webservices definition
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . './../../vendor/autoload.php');

/**
 * This class is used to get an experience
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
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
                'id' => new external_value(PARAM_INT, 'course ID'),
            ]
        );
    }

    /**
     * Get a course schema
     *
     * @param  int   $id The course ID
     * @return array The result of the operation
     */
    public static function get_course_schema($id)
    {
        // checke parameters
        $params = self::validate_parameters(self::get_course_schema_parameters(), ['id' => $id]);

        return [
            'result' => true,
        ];
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
                'result' => new external_value(PARAM_BOOL, 'The result of the operation'),
            ]
        );
    }
      
}
