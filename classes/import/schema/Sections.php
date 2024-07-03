<?php

/**
 * Sections class
 *
 * @package     local_data_transfer
 * @category    Importer
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\import\schema;

/**
 * Class Section
 *
 * A class responsible for importing Section data from JSON.
 */
class Sections
{
    public int $recordid;
    public string $uuid;
    public int $courseid;
    public array $errors = [];
    public array $sections = [];


    /**
     * Constructor
     * 
     * @param int $recordid ID of the record
     * @param string $json JSON string containing course data
     */
    public function __construct(int $recordid, string $json)
    {
        $this->recordid = $recordid;
        $this->set_from_json($json);
    }

    /**
     * Set the course data based on JSON input
     * 
     * @param string $json JSON string containing course data
     * @return void
     */
    public function set_from_json(string $json): void
    {
        $data = json_decode($json, true);

        if (!isset($data['uuid'])) {
            $this->errors[] = 'Section uuid is not set';
        } else {
            $this->uuid = $data['uuid'];
        }

        $this->sections = $data['sections'];
    }
}
