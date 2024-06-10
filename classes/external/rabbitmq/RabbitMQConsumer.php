<?php
namespace local_data_transfer\external\rabbitmq;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Channel\AMQPChannel;

class RabbitMQConsumer {
    private $connection;
    private $channel;
    private $queue;

    public function __construct(AMQPStreamConnection $connection, string $queue) {
        $this->connection = $connection;
        $this->channel = $this->connection->channel();
        $this->queue = $queue;
    }

    public function consume(callable $callback) {
        $this->channel->basic_consume($this->queue, '', false, true, false, false, $callback);

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    public function stopConsuming() {
        $this->channel->basic_cancel('');
    }

    public function close() {
        $this->channel->close();
        $this->connection->close();
    }
}
