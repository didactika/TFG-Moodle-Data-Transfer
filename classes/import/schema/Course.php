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
    public int $courseid;
    public ?Header $course_header = null;
    public ?Content $course_content = null;
    public ?Groups $course_groups = null;
    public ?Groupings $course_groupings = null;

    /**
     * Constructor
     * 
     * @param string $json JSON string containing course data
     */
    public function __construct(string $json)
    {
        $this->import_from_json($json);
    }

    /**
     * Set the course data based on JSON input
     * 
     * @param string $json JSON string containing course data
     * @return void
     */
    public function import_from_json(string $json): void
    {
        $data = json_decode($json, true);

        $this->courseid = $data['courseid'] ?? 0;

        if (isset($data['header'])) {
            $this->course_header = new Header();
            $this->course_header->import_from_json(json_encode($data['header']));
        }

        if (isset($data['content'])) {
            $this->course_content = new Content();
            $this->course_content->import_from_json(json_encode($data['content']));
        }

        if (isset($data['groups'])) {
            $this->course_groups = new Groups();
            $this->course_groups->import_from_json(json_encode($data['groups']));
        }

        if (isset($data['groupings'])) {
            $this->course_groupings = new Groupings();
            $this->course_groupings->import_from_json(json_encode($data['groupings']));
        }
    }
}