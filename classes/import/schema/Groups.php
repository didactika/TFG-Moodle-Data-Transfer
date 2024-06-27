<?php

/**
 * Groupings class
 *
 * @package     local_data_transfer
 * @category    Importer
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\import\schema;

/**
 * Class Groups
 *
 * A class responsible for importing groups data from JSON.
 */
class Groups
{
    public $groups = [];

    /**
     * Set the groups data based on JSON input
     * 
     * @param string $json JSON string containing groups data
     * @return void
     */
    public function import_from_json(string $json): void
    {
        $this->groups = json_decode($json);
    }

    public function show_groups_data (): void
    {
        print_r($this->groups);
    }
}