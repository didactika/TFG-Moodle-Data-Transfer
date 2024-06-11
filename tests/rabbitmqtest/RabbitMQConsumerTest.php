<?php

namespace local_data_transfer\tests\rabbitmqtest;

use PHPUnit\Framework\TestCase;
use local_data_transfer\external\rabbitmq\Connection;
use local_data_transfer\external\rabbitmq\Publisher;
use local_data_transfer\external\rabbitmq\Consumer;

require_once(__DIR__ . '/../../vendor/autoload.php');

class RabbitMQConsumerTest extends TestCase {
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

    public function testConsume() {
        $publisher = new Publisher($this->connection->getConnection(), 'test_exchange');
        $publisher->publish('Test Message', 'test_routing_key');
        $publisher->close();

        // Verificar si el mensaje fue consumido
        $messageReceived = false;
        $consumer = new Consumer($this->connection, function ($msg) use (&$messageReceived) {
            $messageReceived = ($msg->body === 'Test Message');
        });

        // Configurar el consumidor para escuchar el mensaje
        $consumer('test_queue', 'test_exchange', 'test_routing_key');

        $this->assertTrue($messageReceived);
    }
}
