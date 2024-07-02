<?php

/**
 * Course class
 *
 * @package     local_data_transfer
 * @category    Importer
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\import\schema;

/**
 * 
 * A class responsible for importing course data from JSON.
 */
class Course
{
    public string $uuid;
    public int $recordid;
    public ?Header $course_header = null;
    public ?array $errors = null;

    /**
     * Constructor
     * 
     * @param string $json JSON string containing course data
     */
    public function __construct(string $json, $recordid = null)
    {
        $this->set_from_json($json);
        $this->recordid = $recordid;
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
            $errors[] = 'Course uuid is not set';
            return;
        }

        $this->uuid = $data['uuid'];

        if (isset($data['header'])) {
            $this->course_header = new Header();
            $this->course_header->import_from_json(json_encode($data['header']));
        }
    }

    /**
     * Get course data to create the course
     * 
     * @return array
     */
    public function get_data_to_create_course(): array
    {
        // verify if exist error, send event and delete from db then return empty array to process in pending commands
        if ($this->errors || !$this->is_valid_data()) {
            print_r("VALID DATA" . $this->is_valid_data());
            echo "Error in course data\n";
            return [];
        }

        return [
            'fullname' => $this->course_header->general->fullname,
            'shortname' => $this->course_header->general->shortname,
            'categoryid' => $this->course_header->general->category,
            'idnumber' => $this->course_header->general->idnumber,
        ];
    }



    public function is_valid_data()
    {
        global $DB;
        $errors = [];

        if (!$this->course_header) {
            $errors[] = 'Course header is not set';
        }

        $errors[]  = $this->course_header->is_valid_data($errors);

        if ($DB->record_exists('course', ['shortname' => $this->course_header->general->shortname])) {
            $errors[] = "Shortname ({$this->course_header->general->shortname}) is already taken.";
        }

        if ($DB->record_exists('course', ['fullname' => $this->course_header->general->fullname])) {
            $errors[] = "fullname ({$this->course_header->general->fullname}) is already taken.";
        }

        if ($DB->record_exists('course', ['idnumber' => $this->course_header->general->idnumber])) {
            $errors[] = "idnumber ({$this->course_header->general->idnumber}) is already taken.";
        }



        if (!empty($errors)) {
            return false;
        }

        return true;
    }
}
