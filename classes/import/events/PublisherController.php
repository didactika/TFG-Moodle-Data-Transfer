<?php

/**
 * Publisher class
 *
 * @package     local_data_transfer
 * @category    Importer
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\import\events;

use local_data_transfer\external\rabbitmq\Connection;
use PhpAmqpLib\Message\AMQPMessage;

require_once(__DIR__ . '/../../../../../config.php');

class PublisherController
{
    private $host;
    private $port;
    private $user;
    private $password;
    private $exchange;
    private $vhost;
    private $connection;
    private $channel;

    /**
     * PublisherController constructor.
     * Initializes RabbitMQ connection settings and creates the connection.
     */
    public function __construct()
    {
        $settings = get_config('local_data_transfer');
        $this->host = $settings->external_rabbitmq_host;
        $this->port = $settings->external_rabbitmq_port;
        $this->user = $settings->external_rabbitmq_user;
        $this->password = $settings->external_rabbitmq_password;
        $this->exchange = $settings->external_rabbitmq_exchange;
        $this->vhost = $settings->external_rabbitmq_vhost;

        $this->create_connection();
    }

    /**
     * Creates a connection to RabbitMQ.
     *
     * @return void
     */
    private function create_connection(): void
    {
        $this->connection = new Connection($this->host, $this->port, $this->user, $this->password, $this->vhost);
        $this->channel = $this->connection->get_channel();
    }

    /**
     * Creates an AMQP message.
     *
     * @param array $record The record data to be sent.
     * @param string $type The type of the message.
     * @return AMQPMessage The created AMQP message.
     */
    private function create_message(array $record, string $type): AMQPMessage
    {
        return new AMQPMessage(
            json_encode($record),
            [
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                'type' => get_config('local_data_transfer', 'external_appid') . '.' . $type,
                'timestamp' => time(),
                'delivery_mode' => 2,
                'content_type' => 'application/json'
            ]
        );
    }

    /**
     * Sends an AMQP message.
     *
     * @param AMQPMessage $msg The message to be sent.
     * @return void
     */
    private function send_message(AMQPMessage $msg): void
    {
        $this->channel->basic_publish($msg, $this->exchange);   
        $this->connection->close();
    }

    /**
     * Sends an error message to RabbitMQ.
     *
     * @param array $errors The array of errors.
     * @param string $message The error message.
     * @return void
     */
    public function error_message(array $errors, string $message): void
    {
        $record = [
            'message' => $message,
            'errors' => $errors,
            'timecreated' => time(),
        ];

        $msg = $this->create_message($record, 'error');
        $this->send_message($msg);        
    }

    /**
     * Sends a success message to RabbitMQ.
     *
     * @param array $data The array of data.
     * @param string $message The success message.
     * @return void
     */
    public function success_message(array $data, string $message): void
    {
        $record = [
            'message' => $message,
            'data' => $data,
            'timecreated' => time(),
        ];

        $msg = $this->create_message($record, 'success');
        $this->send_message($msg);        
    }
}