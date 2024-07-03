<?php

/**
 * Course class
 *
 * @package     local_data_transfer
 * @category    Importer
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\import\schema;

use local_data_transfer\import\events\PublisherController;

/**
 * A class responsible for importing course data from JSON.
 */
class Course
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
     * Set the course data based on JSON input
     * 
     * @param string $json JSON string containing course data
     * @return void
     */
    public function set_from_json(string $json): void
    {
        $data = json_decode($json, true);

        if (!isset($data['uuid'])) {
            $this->errors[] = 'Course uuid is not set';
        } else {
            $this->uuid = $data['uuid'];
        }

        if (isset($data['header'])) {
            $this->course_header = new Header();
            $this->course_header->import_from_json(json_encode($data['header']));
        } else {
            $this->errors[] = 'Course header is not set';
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
            $this->fail_creation();
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
    public function succes_creation(int $courseid): void
    {
        global $DB;
        $DB->delete_records('transfer_pending_commands', ['id' => $this->recordid]);
        $this->courseid = $courseid;
        
        $record = [
            "courseid" => $courseid,
            "migrationuuid" => $this->uuid,
            "timecreated" => date('Y-m-d H:i:s', time()),
            "timemodified" => date('Y-m-d H:i:s', time())
        ]; 

        $DB->insert_record('transfer_course_created', $record, false);

        // Send success event
        $publisher = new PublisherController();
        $publisher->success_message(['courseid' => $courseid], 'Course creation successful.');
    }

    /**
     * Mark course creation as failed
     * 
     * @return void
     */
    public function fail_creation(): void
    {
        // Send error event
        $publisher = new PublisherController();
        $publisher->error_message($this->errors, 'Course creation failed. in record id: ' . $this->recordid);
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
            $this->errors[] = 'Course header is not set';
            return false;
        }

        $this->errors = array_merge($this->errors, $this->course_header->is_valid_data());

        if ($DB->record_exists('course', ['shortname' => $this->course_header->general->shortname])) {
            $this->errors[] = "Shortname ({$this->course_header->general->shortname}) is already taken.";
        }

        if ($DB->record_exists('course', ['fullname' => $this->course_header->general->fullname])) {
            $this->errors[] = "Fullname ({$this->course_header->general->fullname}) is already taken.";
        }

        if ($DB->record_exists('course', ['idnumber' => $this->course_header->general->idnumber])) {
            $this->errors[] = "ID number ({$this->course_header->general->idnumber}) is already taken.";
        }

        return empty($this->errors);
    }
}
