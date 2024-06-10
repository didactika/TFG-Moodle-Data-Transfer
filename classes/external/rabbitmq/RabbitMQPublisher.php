<?php 
/**
 * RabbitMQ Publisher
 *
 * @package     local_data_transfer
 * @category    external_rabbitmq_connection
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_data_transfer\external\rabbitmq;

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQPublisher {
    private $connection;
    private $channel;
    private $exchange;

    public function __construct(AMQPStreamConnection $connection, $exchange = '') {
        $this->connection = $connection;
        $this->exchange = $exchange;
        $this->channel = $this->connection->channel();
    }

    public function publish($message, $routingKey = '') {
        $msg = new AMQPMessage($message, [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

        $this->channel->basic_publish($msg, $this->exchange, $routingKey);
    }

    public function close() {
        $this->channel->close();
    }
}