<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();


$record = [
    "uuid" => '40aa0418-aa9a-5fe0-85d2-77c34e29731b',
    "header" => [
        "general" => [
            "category" => 2,
            "fullname" => "SNCNC1-2023",
            "shortname" => "SNCNC1-2023",
            "idnumber" => "40aa0418-aa9a-5fe0-85d2-77c34e29731b||SNCNC1-2023"
        ]
    ],
    // "content" => [
    //     "sections" => [
    //         [
    //             "id" => 1,
    //             "section" => 0,
    //             "name" => null,
    //             "visible" => 1,
    //             "availability" => null
    //         ],
    //         [
    //             "id" => 2,
    //             "section" => 1,
    //             "name" => null,
    //             "visible" => 1,
    //             "availability" => null
    //         ],
    //         [
    //             "id" => 3,
    //             "section" => 2,
    //             "name" => null,
    //             "visible" => 1,
    //             "availability" => null
    //         ],
    //         [
    //             "id" => 4,
    //             "section" => 3,
    //             "name" => null,
    //             "visible" => 1,
    //             "availability" => null
    //         ],
    //         [
    //             "id" => 5,
    //             "section" => 4,
    //             "name" => null,
    //             "visible" => 1,
    //             "availability" => null
    //         ]
    //     ]
    // ],
    // "groups" => [],
    // "groupings" => []
];



$msg = new AMQPMessage(
    json_encode($record),
    array(
        'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
        'type' => 'moodle.dominos.course-base-created',
        'timestamp' => time(),
        'delivery_mode' => 2,
        'content_type' => 'application/json'
    )
);


$channel->basic_publish($msg, 'eto');


echo ' [x] Sent ', json_encode($record), "\n";

$channel->close();
$connection->close();
