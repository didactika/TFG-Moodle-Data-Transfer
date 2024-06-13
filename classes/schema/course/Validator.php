<?php

/**
 * Course validator
 *
 * @package     local_data_transfer
 * @category    Importer
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\schema\course;

class Validator
{
    /**
     * Validate if header has the correct properties
     */
    public static function validate_header($schema): bool
    {
        $requiredProperties = [
            'courseuuid',
            'header' => [
                'general' => [
                    'category',
                    'fullname',
                    'shortname',
                    'idnumber'
                ]
            ]
        ];

        self::validate_properties($schema, $requiredProperties);

        return true;
    }


    /**
     * Validate if properties are present in the schema
     * or in the nested properties
     * 
     * @param object $schema
     * @param array $properties
     * @param string $path
     * 
     * @throws \Exception
     */
    private static function validate_properties($schema, $properties, $path = '')
    {
        foreach ($properties as $key => $value) {
            if (is_array($value)) {
                if (!property_exists($schema, $key)) {
                    throw new \Exception("Property '$path$key' is required");
                }
                self::validate_properties($schema->$key, $value, $path . "$key.");
            } else {
                if (!property_exists($schema, $value)) {
                    throw new \Exception("Property '$path$value' is required");
                }
            }
        }
    }
}
