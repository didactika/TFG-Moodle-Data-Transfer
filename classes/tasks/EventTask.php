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

use local_data_transfer\external\rabbitmq\Connection;
use local_data_transfer\external\rabbitmq\Consumer;
use PhpAmqpLib\Message\AMQPMessage;


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

        $connection = new Connection($host, $port, $user, $password);

        // Define the message processing callback
        $messageCallback = function (AMQPMessage $msg) {
            // Process the message
            echo "Processing message: " . $msg->body . PHP_EOL;
        };

        // Create and run the consumer
        $consumer = new Consumer($connection, $messageCallback);
        $consumer->execute('your_queue_name', 'your_exchange_name', 'your_routing_key');
    }
}
