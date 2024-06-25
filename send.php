<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('task_queue', false, true, false, false);

$record = [
    'uuid' => "ddsddsd",
    'fired_at' => date('Y-m-d\TH:i:s\Z', time()),
    'final_grade' => "fff",
];


$msg = new AMQPMessage(
    json_encode($record),
    array(
        'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
        'type' => 'test.dominos',
        'timestamp' => time(),
        'delivery_mode' => 2,
        'content_type' => 'aplication/json'
    )
);


$channel->basic_publish($msg, 'eto');

echo ' [x] Sent ', json_encode($record), "\n";

$channel->close();
$connection->close();
