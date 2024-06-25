<?php

namespace local_data_transfer\external\rabbitmq;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class Connection {
    private $host;
    private $port;
    private $user;
    private $password;
    private $connection;
    private $channel;
    private $vhost;

    /**
     * Constructor for the Connection class.
     *
     * @param string $host
     * @param int $port
     * @param string $user
     * @param string $password
     */
    public function __construct($host, $port, $user, $password, $vhost) {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
        $this->vhost = $vhost;
        $this->connect();
    }

    /**
     * Establishes a connection to RabbitMQ
     *
     * @return AMQPStreamConnection
     */
    private function connect() {
        $this->connection = new AMQPStreamConnection(
            $this->host,
            $this->port,
            $this->user,
            $this->password,
            $this->vhost
        );
        $this->channel = $this->connection->channel();
    }

    /**
     * Gets the channel.
     *
     * @return AMQPChannel
     */
    public function get_channel() {
        return $this->channel;
    }

    /**
     * Closes the connection and the channel.
     */
    public function close() {
        $this->channel->close();
        $this->connection->close();
    }

    /**
     * Gets the connection.
     *
     * @return AMQPStreamConnection
     */
    public function get_connection() {
        return $this->connection;
    }
}
