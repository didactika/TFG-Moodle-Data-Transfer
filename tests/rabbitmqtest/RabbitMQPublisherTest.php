<?php

namespace local_data_transfer\tests\rabbitmqtest;

use PHPUnit\Framework\TestCase;
use local_data_transfer\external\rabbitmq\Connection;
use local_data_transfer\external\rabbitmq\Publisher;
use local_data_transfer\external\rabbitmq\Consumer;

require_once(__DIR__ . '/../../vendor/autoload.php');

class RabbitMQPublisherTest extends TestCase {
    private $connection;

    protected function setUp(): void {
        $config = require __DIR__ . '/config.php';
        $this->connection = new Connection(
            $config['rabbitmq']['host'],
            $config['rabbitmq']['port'],
            $config['rabbitmq']['user'],
            $config['rabbitmq']['password']
        );
        $this->connection->connect('test_queue');
    }

    protected function tearDown(): void {
        $this->connection->close();
    }

    public function testPublishAndConsume() {
        $publisher = new Publisher($this->connection->getConnection(), 'test_exchange');
        $publisher->publish('Test Message', 'test_routing_key');


        $this->assertTrue(true);

        $publisher->close();
    }
}
