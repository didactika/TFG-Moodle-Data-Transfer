<?php

/**
 * Course class
 *
 * @package     local_data_transfer
 * @category    Importer
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\import\schema;


/**
 * A class responsible for importing course data from JSON.
 */
class Course extends Migrator
{
    public string $uuid;
    public int $recordid;
    public ?Header $course_header = null;
    public array $errors = [];
    private $courseid = null;

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
     * Set the course ID
     * 
     * @param int $courseid
     */
    public function set_courseid(int $courseid): void
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
        $data = json_decode($json, true);

        if (!isset($data['uuid'])) {
            $this->add_error('Course uuid is not set');
        } else {
            $this->uuid = $data['uuid'];
        }

        if (isset($data['header'])) {
            $this->course_header = new Header();
            $this->course_header->import_from_json(json_encode($data['header']));
        } else {
            $this->add_error('Course header is not set');
        }
    }

    /**
     * Get course data to create the course
     * 
     * @return array
     */
    public function get_data_to_create_course(): array
    {
        // Verify if exist error, send event and delete from db then return empty array to process in pending commands
        if (!$this->is_valid_data()) {
            $this->fail_creation("Course creation failed" , $this->errors , ['recordid' => $this->recordid]);
            return [];
        }

        return [
            'fullname' => $this->course_header->general->fullname,
            'shortname' => $this->course_header->general->shortname,
            'categoryid' => $this->course_header->general->category,
            'idnumber' => $this->course_header->general->idnumber,
        ];
    }

    /**
     * Mark course creation as successful
     * 
     * @param int $courseid ID of the created course
     * @return void
     */
    public function success(): void
    {
        global $DB;
        $DB->delete_records('transfer_pending_commands', ['id' => $this->recordid]);

        $record = [
            "courseid" => $this->courseid,
            "migrationuuid" => $this->uuid,
            "timecreated" => date('Y-m-d H:i:s', time()),
            "timemodified" => date('Y-m-d H:i:s', time())
        ];

        $DB->insert_record('transfer_course_created', $record, false);

        parent::success_creation("Course created successfully", ['courseid' => $this->courseid]);
        
        echo "[+] Course created by id: {$this->courseid} \n";
    }


    /**
     * Validate course data
     * 
     * @return bool
     */
    public function is_valid_data(): bool
    {
        global $DB;

        if (!$this->course_header) {
            $this->add_error('Course header is not set');
            return false;
        }

        $this->errors = array_merge($this->errors, $this->course_header->is_valid_data());

        if ($DB->record_exists('course', ['shortname' => $this->course_header->general->shortname])) {
            $this->add_error("Shortname ({$this->course_header->general->shortname}) is already taken.");
        }

        if ($DB->record_exists('course', ['idnumber' => $this->course_header->general->idnumber])) {
            $this->add_error("ID number ({$this->course_header->general->idnumber}) is already taken.");
        }

        return empty($this->errors);
    }
}
