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

    public function execute(string $queueName = '', string $exchangeName = '', string $routingKey = ''): void
    {
        $channel = $this->connection->get_channel();

        echo " [*] Connection open, channel id: " . json_encode($channel->getChannelId()) . "\n";

        $channel->queue_declare($queueName, true);
        $channel->queue_bind($queueName, $exchangeName, $routingKey);

        $callback = [Dispatcher::class, 'callback_dispatcher'];

        $channel->basic_qos(null, 1, false);
        $channel->basic_consume(
            $queueName,
            '',
            false,
            false,
            false,
            false,
            $callback
        );

        $noMoreMessagesFlag = false;

        try {
            while ($channel->is_consuming()) {
                $channel->wait(null, true, 5);

                list($queue, $messageCount) = $channel->queue_declare($queueName, true);

                if ($messageCount == 0) {
                    if ($noMoreMessagesFlag) {
                        echo " [*] No more messages in the queue. Exiting...\n";
                        break;
                    }
                    $noMoreMessagesFlag = true;
                } else {
                    $noMoreMessagesFlag = false;
                }
            }
        } catch (\Exception $e) {
            echo " [!] An error occurred: " . $e->getMessage() . "\n";
        } finally {
            $channel->close();
            $this->connection->close();
        }
    }
}
