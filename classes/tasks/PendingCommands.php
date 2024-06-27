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
     */
    public function execute()
    {
        echo "Executing pending commands\n";
    }
}
