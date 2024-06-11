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
    private $queue;

    /**
     * Constructor for the Connection class.
     *
     * @param string $host
     * @param int $port
     * @param string $user
     * @param string $password
     */
    public function __construct($host, $port, $user, $password) {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Establishes a connection to RabbitMQ and declares a queue.
     *
     * @param string $queue
     * @return AMQPStreamConnection
     */
    public function connect($queue) {
        $this->queue = $queue;
        $this->connection = new AMQPStreamConnection(
            $this->host,
            $this->port,
            $this->user,
            $this->password
        );
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare($this->queue, false, true, false, false);
        return $this->connection;
    }

    /**
     * Gets the channel.
     *
     * @return AMQPChannel
     */
    public function getChannel() {
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
    public function getConnection() {
        return $this->connection;
    }
}
