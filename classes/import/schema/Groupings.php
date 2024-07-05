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

require_once(__DIR__ . '/../../../../../config.php');

global $CFG;

require_once($CFG->dirroot . '/group/lib.php');
/**
 * Class Grouopings
 * A class responsible for importing groupings data from JSON.
 */
class Groupings extends Migrator
{

    public int $recordid;
    public string $uuid = '';
    public int $courseid;
    public array $errors = [];
    public array $groupings = [];


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
            $this->add_error('Proccess uuid is not set');
        } else {
            $this->uuid = $data['uuid'];
        }

        if (!isset($data['groupings'])) {
            $this->add_error('Missing groupings');
            return;
        }

        $this->groupings = $data['groupings'];
    }


    /** 
     * Implement the method to validate the data
     * 
     * @return bool
     */
    public function is_valid_data(): bool
    {

        if (empty($this->groupings)) {
            $this->add_error('No groupings to process');
        }

        foreach ($this->groupings as $index => $grouping) {
            if (!isset($grouping['name'])) {
                $this->add_error("Groupings name is not set in iteration {$index}");
            }
        }


        return empty($this->errors);
    }

    /**
     * Implement the method to create groupings
     * 
     * @return void
     */
    public function create_groupings()
    {
        if (!$this->is_valid_data()) {
            $this->fail_creation("Failed section groupings", $this->errors, ['recordid' => $this->recordid]);
            return;
        }


        foreach ($this->groupings as $grouping) {
            $grouping = (object) $grouping;
            $grouping->courseid = $this->courseid;
            groups_create_grouping($grouping);
        }

        $this->success();
    }

    /**
     * Mark course creation as successful
     * 
     * @return void
     */
    public function success(): void
    {
        global $DB;
        $DB->delete_records('transfer_pending_commands', ['id' => $this->recordid]);


        parent::success_creation("Groupings created successfully", ['courseid' => $this->courseid]);

        echo "[+] Groupings created in course id: {$this->courseid} \n";
    }
}
