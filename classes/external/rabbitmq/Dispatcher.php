<?php

/**
 * RabbitMQ Publisher
 *
 * @package     local_data_transfer
 * @category    external_rabbitmq_connection
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\external\rabbitmq;

class Dispatcher
{

    public static function callback_dispatcher($msg)
    {
        $properties = $msg->getBody();
        $body = $msg->get_properties();
        // TODO validate event header in event validator ? 
        // TODO Get type and redirect 
    }
}
