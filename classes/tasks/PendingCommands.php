<?php

/**
 * PendingCommands class
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\tasks;

use local_data_transfer\Constants;
use local_data_transfer\import\schema\Course;

require_once(__DIR__ . '/../../vendor/autoload.php');

defined('MOODLE_INTERNAL') || die();

class PendingCommands extends \core\task\scheduled_task
{
    /**
     * Return the task's name as shown in admin screens.
     *
     * @return string
     */
    public function get_name()
    {
        return get_string('task:pending_commands', 'local_data_transfer');
    }

    /**
     * Execute the task.
     * 
     * This will retrieve in ascending order all the pending commands from the database and process them.
     * Important: will proccess in type order
     *   
     */
    public function execute()
    {
        global $DB;
        if (!$pending_commands = $DB->get_records('pending_commands', null, 'type')) {
            return;
        }

        foreach ($pending_commands as $pending_command) {
            $type = $pending_command->type;
            $json = $pending_command->jsondata;

            
            if ($type == Constants::EVENT_TYPES['COURSE_BASE_CREATED']) {
                $course = new Course($json);
                $course->show_course_data();
            };
        }
    }
}
