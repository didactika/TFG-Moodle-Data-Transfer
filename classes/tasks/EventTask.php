<?php

/**
 * EventTask class
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_data_transfer\tasks;


require_once(__DIR__ . '/../../vendor/autoload.php');

use local_data_transfer\external\rabbitmq\Connection;
use local_data_transfer\external\rabbitmq\Consumer;


defined('MOODLE_INTERNAL') || die();

class EventTask extends \core\task\scheduled_task
{
    /**
     * Return the task's name as shown in admin screens.
     *
     * @return string
     */
    public function get_name()
    {
        return get_string('task:event_task', 'local_data_transfer');
    }

    /**
     * Execute the task.
     */
    public function execute()
    {
        $settings = get_config('local_data_transfer');
        
        $host = $settings->external_rabbitmq_host;
        $port = $settings->external_rabbitmq_port;
        $user = $settings->external_rabbitmq_user;
        $password = $settings->external_rabbitmq_password;
        $queue = $settings->external_rabbitmq_queue;
        $exchange = $settings->external_rabbitmq_exchange;
        $vhost = $settings->external_rabbitmq_vhost;

        $connection = new Connection($host, $port, $user, $password, $vhost);

        $consumer = new Consumer($connection);
        $consumer->execute($queue, $exchange);
    }
}
