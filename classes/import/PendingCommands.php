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

require_once(__DIR__ . '/../../../../config.php');
global $CFG;
require_once($CFG->dirroot . '/course/externallib.php');

use local_data_transfer\Constants;
use local_data_transfer\import\schema\Course;
use local_data_transfer\import\schema\Sections;
use core_course_external;


class PendingCommands
{

    public $courses;
    public $sections;


    public function __construct()
    {
        $this->courses = [];
        $this->sections = [];
    }


    public function dispatcher()
    {
        $pending_commands = $this->get_pending_commands();

        foreach ($pending_commands as $pending_command) {

            if ($pending_command->type == Constants::EVENT_TYPES['COURSE_BASE_CREATED']) {
                $this->courses[] =  new Course($pending_command->id, $pending_command->jsondata);
            }
            if ($pending_command->type == Constants::EVENT_TYPES['COURSE_SECTION_CREATED']) {
                $this->sections[] = new Sections($pending_command->id, $pending_command->jsondata);
            }
        }
    }

    /**
     * Execute the pending commands
     */
    public function execute()
    {
        $this->executer_courses();
        $this->executer_sections();
    }

    /**
     * Get pending commands from the database
     * 
     * @return array
     */
    private function get_pending_commands()
    {
        global $DB;
        return $DB->get_records('transfer_pending_commands', null, 'type');
    }

    /**
     * Create a course in Moodle
     * 
     * @param array $courses Array of courses to create
     * 
     * @return array
     */
    private function executer_courses(): void
    {
        if (empty($this->courses)) {
            echo "[/] No courses to process.\n";
            return;
        }
        try {
            foreach ($this->courses as $index => $course) {
                $course_data = $course->get_data_to_create_course();
                if (empty($course_data)) {
                    continue;
                }
                $created_course = core_course_external::create_courses([$course_data]);
                $course->set_courseid($created_course[0]['id']);
                $course->success();
            }
        } catch (Exception $e) {
            error_log("Error creating course: {$e->getMessage()}");
        }
    }


    /**
     * Create sections in Moodle
     * 
     */
    private function executer_sections(): void
    {
        if (empty($this->sections)) {
            echo "[/] No sections to process.\n";
            return;
        }
        try {
            foreach ($this->sections as $section) {
                $section->create_sections();
            }
        } catch (Exception $e) {
            error_log("Error creating sections: {$e->getMessage()}");
        }
    }
}
