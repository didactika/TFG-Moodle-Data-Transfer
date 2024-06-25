<?php

namespace local_data_transfer\external\rabbitmq;

use local_data_transfer\external\rabbitmq\Dispatcher;

class Consumer
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function execute(string $queue = '', string $exchange = '', string $routing_key = ''): void
    {
        $channel = $this->connection->get_channel();

        echo " [*] Connection open , channel id: " . json_encode($channel->getChannelId()) . "\n";

        $channel->queue_declare($queue, true);
        $channel->queue_bind($queue, $exchange, $routing_key);

        $callback = [Dispatcher::class, 'callback_dispatcher'];

        $channel->basic_qos(null, 2, null);
        $channel->basic_consume(
            $queue,
            '',
            false,
            false,
            false,
            false,
            $callback
        );

        while ($channel->is_consuming()) {
            $channel->wait(null, true, 5);

            list($queue, $messageCount) = $channel->queue_declare($queue, true);

            if ($messageCount == 0) {
                echo " [*] No more messages in the queue. Exiting...\n";
                break;
            }
        }
        $this->connection->close();
    }
}