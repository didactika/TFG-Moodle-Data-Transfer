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

require_once(__DIR__ . '/../../../../../config.php');

global $CFG;
require_once($CFG->dirroot . '/course/externallib.php');

use core_course_external;
use Exception;


/**
 * 
 * A class responsible for importing course data from JSON.
 */
class Course
{
    public int $courseid;
    public ?Header $course_header = null;

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
    }

    /**
     * Get course data to create the course
     * 
     * @return array
     */
    public function get_data_to_create_course(): array
    {
        return [
            'fullname' => $this->course_header->general->fullname,
            'shortname' => $this->course_header->general->shortname,
            'categoryid' => $this->course_header->general->category,
            'idnumber' => $this->course_header->general->idnumber,
        ];
    }

    /**
     * Create a course in Moodle
     * 
     * @param array $courses Array of courses to create
     */
    public static function create_courses(array $courses): void
    {
        try {
            $created_courses = core_course_external::create_courses($courses);
            foreach ($created_courses as $created_course) {
                echo "Created course with ID: {$created_course['id']}\n";
            }
        } catch (Exception $e) {
            echo "Error creating course: " . $e->getMessage() . "\n";
        }
    }
}