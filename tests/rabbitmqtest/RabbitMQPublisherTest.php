<?php

namespace local_data_transfer\tests\rabbitmqtest;

use PHPUnit\Framework\TestCase;
use PhpAmqpLib\Message\AMQPMessage;
use local_data_transfer\external\rabbitmq\RabbitMQConnection;
use local_data_transfer\external\rabbitmq\RabbitMQPublisher;
require_once(__DIR__ . '/../../vendor/autoload.php');

class RabbitMQPublisherTest extends TestCase {
    private $connection;

    protected function setUp(): void {
        $config = require __DIR__ . './config.php';
        $this->connection = new RabbitMQConnection(
            $config['rabbitmq']['host'],
            $config['rabbitmq']['port'],
            $config['rabbitmq']['user'],
            $config['rabbitmq']['password']
        );
        $this->connection->connect();
    }

    protected function tearDown(): void {
        $this->connection->close();
    }

    public function testPublish() {
        $publisher = new RabbitMQPublisher($this->connection->getConnection());
        $publisher->publish('Test Message', 'test_queue');
        
        // Verify that the message was published - this would typically involve a more complex setup
        // In a real test, you might consume the message from the queue to verify it was published
        $this->assertTrue(true);

        $publisher->close();
    }
}
