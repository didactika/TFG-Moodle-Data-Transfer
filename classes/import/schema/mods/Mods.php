<?php

/**
 * Mod class
 *
 * @package     local_data_transfer
 * @category    Importer
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_data_transfer\import\schema\mods;

class Mods extends ModMigrator
{

    public int $recordid;
    public string $uuid = '';
    public int $courseid;
    public array $errors = [];
    public array $mod = [];

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
        if ($this->uuid) {
            $this->get_courseid($this->uuid);
        }
    }
    
     /**
     * Implement the method to set the course ID
     *
     * @param int $courseid
     */
    protected function set_courseid(int $courseid): void
    {
        $this->courseid = $courseid;
    }

    /**
     * Implement the method to add an error message
     *
     * @param string $error
     */
    protected function add_error(string $error): void
    {
        $this->errors[] = $error;
    }
}