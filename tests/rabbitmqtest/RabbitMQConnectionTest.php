<?php

namespace local_data_transfer\tests\rabbitmqtest;

use PHPUnit\Framework\TestCase;
use local_data_transfer\external\rabbitmq\RabbitMQConnection;

require_once(__DIR__ . '/../../vendor/autoload.php');

class RabbitMQConnectionTest extends TestCase {
    private $config;

    protected function setUp(): void {
        $this->config = require __DIR__ . '/config.php'; // Corregido: Agregué '/' para especificar la ruta del archivo
    }

    public function testConnection() {
        $connection = new RabbitMQConnection(
            $this->config['rabbitmq']['host'],
            $this->config['rabbitmq']['port'],
            $this->config['rabbitmq']['user'],
            $this->config['rabbitmq']['password']
        );
        $connection->connect();
        $this->assertInstanceOf('PhpAmqpLib\Connection\AMQPStreamConnection', $connection->getConnection());
        $connection->close();
    }
}
