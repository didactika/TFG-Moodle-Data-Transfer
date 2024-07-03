<?php

/**
 * Header class
 *
 * @package     local_data_transfer
 * @category    Importer
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\import\schema;


/**
 * Class Header
 *
 * A class responsible for importing header data from JSON.
 */
class Header
{
    public $general;

    /**
     * Set the header data based on JSON input
     * 
     * @param string $json JSON string containing header data
     * @return void
     */
    public function import_from_json(string $json): void
    {
        $this->general = json_decode($json)->general;
    }


    /**
     * Check if the data is valid
     * 
     * @return array
     */
    public function is_valid_data(): array
    {
        $errors = [];

        if (empty($this->general->fullname)) {
            $errors[] = 'Course fullname is not set';
        }

        if (empty($this->general->shortname)) {
            $errors[] = 'Course shortname is not set';
        }

        if (empty($this->general->category)) {
            $errors[] = 'Course category id is not set';
        }

        if (empty($this->general->idnumber)) {
            $errors[] = 'Course idnumber is not set';
        }

        return $errors;
    }
}