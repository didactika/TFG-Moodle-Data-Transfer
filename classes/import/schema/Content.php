<?php

/**
 * Content class
 *
 * @package     local_data_transfer
 * @category    Importer
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\import\schema;

/**
 * Class Content
 *
 * A class responsible for importing content data from JSON.
 */
class Content
{
    public $sections = [];

    /**
     * Set the content data based on JSON input
     * 
     * @param string $json JSON string containing content data
     * @return void
     */
    public function import_from_json(string $json): void
    {
        $data = json_decode($json, true);
        foreach ($data['sections'] as $section) {
            $this->sections[] = (object)$section;
        }
    }
}
