<?php

namespace local_data_transfer\external\rabbitmq;

use PhpAmqpLib\Message\AMQPMessage;

class Consumer
{
    private $connection;
    private $messageObject;

    public function __construct(Connection $connection, callable $messageObject)
    {
        $this->connection = $connection;
        $this->messageObject = $messageObject;
    }

    public function __invoke(string $queue = '', string $exchange = '', string $routingKey = ''): void
    {
        $channel = $this->connection->getChannel();

        echo "Connect open, channel id: " . json_encode($channel->getChannelId()) . PHP_EOL;

        $callback = function (AMQPMessage $msg) {
            echo "Consuming event" . PHP_EOL;
            $msg->getChannel()->basic_ack($msg->getDeliveryTag());
            $this->messageReceived($msg);
        };

        $channel->queue_bind($queue, $exchange, $routingKey);
        $channel->basic_qos(null, 1, null);
        $channel->basic_consume($queue, '', false, false, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }

    public function messageReceived(AMQPMessage $msg): void
    {
        if ($this->messageObject === null) {
            return;
        }
        echo "Message received" . PHP_EOL;
        print_r($msg);
        call_user_func($this->messageObject, $msg);
    }
}
