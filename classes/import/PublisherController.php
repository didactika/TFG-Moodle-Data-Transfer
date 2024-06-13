<?php

/**
 * Publisher class
 *
 * @package     local_data_transfer
 * @category    Importer
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\import;

use local_data_transfer\external\rabbitmq\Publisher;
use local_data_transfer\external\rabbitmq\Connection;

require_once(__DIR__ . '/../../config.php');

class PublisherController
{
    private $host; 
    private $port;
    private $user;
    private $password;
    private $queue;
    private $connection;

    public function __construct()
    {
        $settings = get_config('local_data_transfer');  
        $this->host = $settings->host;
        $this->port = $settings->port;
        $this->user = $settings->user;
        $this->password = $settings->password;
        $this->queue = $settings->queue;
        $this->create_connection();
    }

    private function create_connection()
    {
        $this->connection = new Connection($this->host, $this->port, $this->user, $this->password);
        $this->connection->connect($this->queue);
    }


    private function close_connection()
    {
        $this->connection->close();
    }


    public function publish_creating_error_message($message)
    {
        $publisher = new Publisher($this->connection);
        // TODO  render moodle mustache template 
        $template = 'Error creating course: {$message}';
        $publisher->publish($template);        
        $this->close_connection();
    }

}
