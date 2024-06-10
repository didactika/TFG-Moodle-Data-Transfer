<?php
namespace local_data_transfer\tests\rabbitmqtest;

use PHPUnit\Framework\TestCase;
use local_data_transfer\external\rabbitmq\RabbitMQConnection;
use local_data_transfer\external\rabbitmq\RabbitMQPublisher;
use local_data_transfer\external\rabbitmq\RabbitMQConsumer;

require_once(__DIR__ . '/../../vendor/autoload.php');

class RabbitMQConsumerTest extends TestCase {
    private $connection;

    protected function setUp(): void {
        $config = require __DIR__ . '/config.php';
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

    public function testConsume() {
        $consumer = new RabbitMQConsumer($this->connection->getConnection(), 'test_queue');

        $callback = function ($msg) use ($consumer) {
            $this->assertEquals('Test Message', $msg->body);
            $consumer->stopConsuming(); // Para detener el consumo después de recibir el mensaje
        };

        // Publicar un mensaje de prueba en la cola
        $publisher = new RabbitMQPublisher($this->connection->getConnection());
        $publisher->publish('Test Message', 'test_queue');
        $publisher->close();

        // Consumir el mensaje de prueba
        $consumer->consume($callback);
        $consumer->close();
    }
}
