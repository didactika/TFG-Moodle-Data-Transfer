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
 * Class Groupings
 *
 * A class responsible for importing groupings data from JSON.
 */
class Groupings
{
    public $groupings = [];

    /**
     * Set the groupings data based on JSON input
     * 
     * @param string $json JSON string containing groupings data
     * @return void
     */
    public function import_from_json(string $json): void
    {
        $this->groupings = json_decode($json);
    }
}
