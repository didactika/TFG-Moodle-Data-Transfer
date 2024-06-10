<?php
/**
 * RabbitMQ Connection
 *
 * @package     local_data_transfer
 * @category    external_rabbitmq_connection
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\external\rabbitmq;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQConnection {
    private $host;
    private $port;
    private $user;
    private $password;
    private $connection;

    public function __construct($host, $port, $user, $password) {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
    }

    public function connect() {
        $this->connection = new AMQPStreamConnection(
            $this->host,
            $this->port,
            $this->user,
            $this->password
        );
    }

    public function getConnection() {
        return $this->connection;
    }

    public function close() {
        $this->connection->close();
    }
}
