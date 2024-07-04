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
class Groups extends Migrator
{

    public int $recordid;
    public string $uuid = '';
    public int $courseid;
    public array $errors = [];
    public array $groups = [];


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


    /**
     * Implement the method to set the data from JSON
     *
     * @param string $json
     */
    public function set_from_json(string $json): void
    {
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->add_error('Invalid JSON');
            return;
        }

        if (!isset($data['uuid'])) {
            $this->add_error('Section uuid is not set');
        } else {
            $this->uuid = $data['uuid'];
        }

        if (!isset($data['groups'])) {
            $this->add_error('Missing groups');
            return;
        }

        $this->groups = $data['groups'];
    }


    /** 
     * Implement the method to validate the data
     * 
     * @return bool
     */
    public function is_valid_data(): bool
    {

        if (empty($this->groups)) {
            $this->add_error('No sections to process');
        }

        foreach ($this->groups as $index => $group) {
            if (!isset($section['name'])) {
                $this->add_error("Group name is not set in iteration {$index}");
            }
        }


        return empty($this->errors);
    }

    public function create_groups() {
        if (!$this->is_valid_data()) {
            return;
        }

        foreach ($this->groups as $group) {
            $group = (object) $group;
            $group->courseid = $this->courseid;
            $group->recordid = $this->recordid;
            $group->uuid = $this->uuid;
            $group->errors = [];
            $group->members = [];

            $group = new Group($group);
            $group->create_group();
        }
    }
}
