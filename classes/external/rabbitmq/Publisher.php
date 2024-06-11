<?php

namespace local_data_transfer\external\rabbitmq;

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class Publisher {
    private $connection;
    private $channel;
    private $exchange;

    public function __construct(AMQPStreamConnection $connection, $exchange = '') {
        $this->connection = $connection;
        $this->exchange = $exchange;
        $this->channel = $this->connection->channel();
        if ($exchange) {
            $this->channel->exchange_declare($exchange, 'direct', false, true, false);
        }
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
