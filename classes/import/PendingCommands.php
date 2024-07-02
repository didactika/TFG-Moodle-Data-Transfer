<?php

/**
 * 
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\import;

use local_data_transfer\Constants;
use local_data_transfer\import\schema\Course;

require_once(__DIR__ . '/../../../../config.php');

global $CFG;
require_once($CFG->dirroot . '/course/externallib.php');


class PendingCommands
{

    public $courses;

    public $messages;

    public $errors;

    public function __construct()
    {
        $this->courses = [];

        $this->messages = [];

        $this->errors = [];
    }


    public function dispatcher()
    {
        $pending_commands = $this->get_pending_commands();

        foreach ($pending_commands as $pending_command) {
            if ($pending_command->type == Constants::EVENT_TYPES['COURSE_BASE_CREATED']) {
                $course = new Course($pending_command->jsondata, $pending_command->id);
                $course_data = $course->get_data_to_create_course();
                // print_r($course_data);
                if (!empty($course_data)) {
                    $this->courses[] = $course_data;
                }
            }
        }
    }

    public function execute()
    {
        $this->executer_courses();
    }

    /**
     * Get pending commands from the database
     * 
     * @return array
     */
    private function get_pending_commands()
    {
        global $DB;
        return $DB->get_records('pending_commands', null, 'type');
    }

    /**
     * Create a course in Moodle
     * 
     * @param array $courses Array of courses to create
     * 
     * @return array
     */
    private function executer_courses(): array
    {
        try {

            $created_courses = core_course_external::create_courses($this->courses);

            foreach ($created_courses as $created_course) {
                echo "Created course with ID: {$created_course['id']}\n";
                $messages[] = "Created course with ID: {$created_course['id']}";
            }
        } catch (Exception $e) {
            echo "Error creating course: {$e->getMessage()}\n";
        }

        return $messages;
    }
}
