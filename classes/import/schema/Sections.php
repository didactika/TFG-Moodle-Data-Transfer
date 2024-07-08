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

use local_data_transfer\import\schema\Migrator;

require_once(__DIR__ . '/../../../../../config.php');

global $CFG;
require_once($CFG->dirroot . '/course/lib.php');

/**
 * Class Section
 *
 * A class responsible for importing Section data from JSON.
 */
class Sections extends Migrator
{
    public int $recordid;
    public string $uuid = '';
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
     * Set the course data based on JSON input
     * 
     * @param string $json JSON string containing course data
     * @return void
     */
    public function set_from_json(string $json): void
    {
        $data = json_decode($json, true)['data'];

        if (!isset($data['uuid'])) {
            $this->add_error('Section uuid is not set');
        } else {
            $this->uuid = $data['uuid'];
        }

        if (!isset($data['sections'])) {
            $this->add_error('Sections data is not set');
        } else {
            $this->sections = $data['sections'];
        }
    }


    /**
     * Check if the data is valid
     * 
     * @return bool
     */
    public function is_valid_data(): bool
    {
        if (empty($this->sections)) {
            $this->add_error('No sections to process');
        }

        foreach ($this->sections as $index => $section) {
            if (!isset($section['section'])) {
                $this->add_error("Section is not set in iteration {$index}");
            }
            if (!isset($section['visible'])) {
                $this->add_error("Section visible is not set in iteration {$index}");
            }
            if (!array_key_exists('availability', $section)) {
                $this->add_error("Section availability is not set in iteration {$index}");
            }
            if (!array_key_exists('name', $section)) {
                $this->add_error("Section name is not set in iteration {$index}");
            }
        }

        return empty($this->errors);
    }

    /**
     * Create sections in Moodle
     * 
     * @return void
     */
    public function create_sections(): void
    {
        global $DB;
        if (!$this->is_valid_data()) {
            $this->fail_creation("Failed section creation", $this->errors, ['recordid' => $this->recordid]);
            return;
        }

        echo "[+] Creating sections for course. {$this->courseid} \n";

        foreach ($this->sections as $section) {
            
            if($section['section'] == 0) {
                $created_section = $DB->get_record('course_sections', ['course' => $this->courseid , 'section' => 0]);
            }else{
                $created_section = course_create_section($this->courseid, $section['section'] +1);
            }

            $data = new \stdClass();
            $data->name = $section['name'];
            $data->visible = $section['visible'];
            $data->availability = $section['availability'];

            course_update_section($this->courseid, $created_section, $data);
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


        parent::success_creation("Sections created successfully", ['courseid' => $this->courseid]);
        
        echo "[+] Sections created in course id: {$this->courseid} \n";
    }
}
