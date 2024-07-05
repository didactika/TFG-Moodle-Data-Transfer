<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

// $testCourse = [
//     [
//         "shortname" => "SNCNC1-2023-test",
//         "uuid" => "41aa0418-aa9a-5fe0-85d2-77c34e29731b",
//         "idnumber" => "41aa0418-aa9a-5fe0-85d2-77c34e29731b||SNCNC1-2023-test",
//     ],
//     [
//         "shortname" => "ENG101-2023-fall",
//         "uuid" => "f987aab8-7bc0-4f67-8a89-1f342e50d5f1",
//         "idnumber" => "f987aab8-7bc0-4f67-8a89-1f342e50d5f1||ENG101-2023-fall",
//     ],
//     [
//         "shortname" => "MATH202-2023-spring",
//         "uuid" => "d2a8312d-8230-4ff1-9b5f-3d56ae17b092",
//         "idnumber" => "d2a8312d-8230-4ff1-9b5f-3d56ae17b092||MATH202-2023-spring",
//     ],
//     [
//         "shortname" => "HIST303-2023-summer",
//         "uuid" => "e8903b98-b8e4-4c35-86fc-d8e0a8298a77",
//         "idnumber" => "e8903b98-b8e4-4c35-86fc-d8e0a8298a77||HIST303-2023-summer",
//     ],
//     [
//         "shortname" => "BIO404-2023-winter",
//         "uuid" => "b4b6c7f4-7765-40e8-b6c9-707a6b0e8a28",
//         "idnumber" => "b4b6c7f4-7765-40e8-b6c9-707a6b0e8a28||BIO404-2023-winter",
//     ],
//     [
//         "shortname" => "CHEM505-2023-fall",
//         "uuid" => "c65f5b34-1bcd-44d8-86c4-7f30a2b18e0d",
//         "idnumber" => "c65f5b34-1bcd-44d8-86c4-7f30a2b18e0d||CHEM505-2023-fall",
//     ],
//     [
//         "shortname" => "PHYS606-2023-spring",
//         "uuid" => "a74a3b9c-5ed3-4b62-8d21-8e73167cb3a3",
//         "idnumber" => "a74a3b9c-5ed3-4b62-8d21-8e73167cb3a3||PHYS606-2023-spring",
//     ],
//     [
//         "shortname" => "COMP707-2023-summer",
//         "uuid" => "2c2bb4f2-cf1a-4b25-ae68-98f6f12c4e4c",
//         "idnumber" => "2c2bb4f2-cf1a-4b25-ae68-98f6f12c4e4c||COMP707-2023-summer",
//     ],
//     [
//         "shortname" => "ART808-2023-winter",
//         "uuid" => "fc2e7f5b-8e7f-4c6e-a8d6-70944c1ed78e",
//         "idnumber" => "fc2e7f5b-8e7f-4c6e-a8d6-70944c1ed78e||ART808-2023-winter",
//     ],
//     [
//         "shortname" => "MUSIC909-2023-fall",
//         "uuid" => "b847de3f-52ab-4c3d-8b19-8f15f3e9f634",
//         "idnumber" => "b847de3f-52ab-4c3d-8b19-8f15f3e9f634||MUSIC909-2023-fall",
//     ],
//     [
//         "shortname" => "PHIL1010-2023-spring",
//         "uuid" => "f3b3b4f2-4f1a-4b25-ae68-98f6f12c4e4c",
//         "idnumber" => "f3b3b4f2-4f1a-4b25-ae68-98f6f12c4e4c||PHIL1010-2023-spring",
//     ],
//     [
//         "shortname" => "ENG202-2023-spring",
//         "uuid" => "59c0a4a1-df42-4f3c-ae4e-0bcad9e6a1a9",
//         "idnumber" => "59c0a4a1-df42-4f3c-ae4e-0bcad9e6a1a9||ENG202-2023-spring",
//     ],
//     [
//         "shortname" => "SCI303-2023-fall",
//         "uuid" => "4b5d7e0a-c9d6-45a3-b8a3-9d8e6f0b1234",
//         "idnumber" => "4b5d7e0a-c9d6-45a3-b8a3-9d8e6f0b1234||SCI303-2023-fall",
//     ],
//     [
//         "shortname" => "GEOG101-2023-winter",
//         "uuid" => "3a4d5e6f-1b2a-4c5d-8e3b-7d9a6c4e2f6d",
//         "idnumber" => "3a4d5e6f-1b2a-4c5d-8e3b-7d9a6c4e2f6d||GEOG101-2023-winter",
//     ],
//     [
//         "shortname" => "HUM404-2023-summer",
//         "uuid" => "5e6f7a1b-2d4c-3e5f-8a1b-9c0d7a8e6b2c",
//         "idnumber" => "5e6f7a1b-2d4c-3e5f-8a1b-9c0d7a8e6b2c||HUM404-2023-summer",
//     ],
//     [
//         "shortname" => "PSY505-2023-fall",
//         "uuid" => "7f8e9d1c-4b3a-5c6d-8e1b-9a0b7c6e5f4d",
//         "idnumber" => "7f8e9d1c-4b3a-5c6d-8e1b-9a0b7c6e5f4d||PSY505-2023-fall",
//     ],
//     [
//         "shortname" => "SOC606-2023-winter",
//         "uuid" => "9d0e1a2b-5c4d-3e2f-8a1b-7c6d5a4e3f2b",
//         "idnumber" => "9d0e1a2b-5c4d-3e2f-8a1b-7c6d5a4e3f2b||SOC606-2023-winter",
//     ],
//     [
//         "shortname" => "PHIL707-2023-spring",
//         "uuid" => "0e1a2b3c-4d5e-6f7a-8b1c-9d0e7c6b5f4d",
//         "idnumber" => "0e1a2b3c-4d5e-6f7a-8b1c-9d0e7c6b5f4d||PHIL707-2023-spring",
//     ],
//     [
//         "shortname" => "ANTH808-2023-summer",
//         "uuid" => "1b2c3d4e-5f6a-7b8c-9d0e-1f2e3d4c5b6a",
//         "idnumber" => "1b2c3d4e-5f6a-7b8c-9d0e-1f2e3d4c5b6a||ANTH808-2023-summer",
//     ],
//     [
//         "shortname" => "LIT909-2023-fall",
//         "uuid" => "2c3d4e5f-6a7b-8c9d-0e1f-2d3c4b5a6e7f",
//         "idnumber" => "2c3d4e5f-6a7b-8c9d-0e1f-2d3c4b5a6e7f||LIT909-2023-fall",
//     ]
// ];

$testCourse = [
    [
        "shortname" => "SNCNC1-2023-test",
        "uuid" => "f987aab8-7bc0-4f67-8a89-1f342e50d5f1",
        "idnumber" => "f987aab8-7bc0-4f67-8a89-1f342e50d5f1||SNCNC1-2023-test",
    ],
];

foreach ($testCourse as $course) {
    echo "Sending course: " . $course['shortname'] . "\n";
    $record = [
        "uuid" => $course['uuid'],
        "header" => [
            "general" => [
                "category" => 2,
                "fullname" => "SNCNC1-2023-test",
                "shortname" => $course['shortname'],
                "idnumber" => $course['idnumber'],
            ]
        ],
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
}







$record = [
    "uuid" => 'f987aab8-7bc0-4f67-8a89-1f342e50d5f1',
    "sections" => [
        [
            "id" => 1,
            "section" => 0,
            "name" => "Primera sección",
            "visible" => 1,
            "availability" => null
        ],
        [
            "id" => 2,
            "section" => 1,
            "name" => "Segunda sección",
            "visible" => 1,
            "availability" => null
        ],
        [
            "id" => 3,
            "section" => 2,
            "name" => "Tercera sección",
            "visible" => 1,
            "availability" => null
        ],
        [
            "id" => 4,
            "section" => 3,
            "name" => "Cuarta sección",
            "visible" => 1,
            "availability" => null
        ],
        [
            "id" => 5,
            "section" => 4,
            "name" => "Quinta sección",
            "visible" => 1,
            "availability" => null
        ]
    ]
];

$msg = new AMQPMessage(
    json_encode($record),
    array(
        'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
        'type' => 'moodle.dominos.course-section-created',
        'timestamp' => time(),
        'delivery_mode' => 2,
        'content_type' => 'application/json'
    )
);

$channel->basic_publish($msg, 'eto');

$record = [
    "uuid" => 'f987aab8-7bc0-4f67-8a89-1f342e50d5f1',
    "groups" => [
        [
            "name" => "aaaaa",
            "idnumber" => "aaaa",
            "description" => "<p dir=\"ltr\" style=\"text-align: left;\">aaaa</p>"
        ],
        [
            "name" => "bbbbb",
            "idnumber" => "bbbb",
            "description" => "<p dir=\"ltr\" style=\"text-align: left;\">bbbb</p>"
        ],
        [
            "name" => "ccccc",
            "idnumber" => "cccc",
            "description" => "<p dir=\"ltr\" style=\"text-align: left;\">cccc</p>"
        ],
        [
            "name" => "ddddd",
            "idnumber" => "dddd",
            "description" => "<p dir=\"ltr\" style=\"text-align: left;\">dddd</p>"
        ],
        [
            "name" => "eeeee",
            "idnumber" => "eeee",
            "description" => "<p dir=\"ltr\" style=\"text-align: left;\">eeee</p>"
        ]
    ]
];

$msg = new AMQPMessage(
    json_encode($record),
    array(
        'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
        'type' => 'moodle.dominos.course-groups-created',
        'timestamp' => time(),
        'delivery_mode' => 2,
        'content_type' => 'application/json'
    )
);


$channel->basic_publish($msg, 'eto');


$record = [
    "uuid" => 'f987aab8-7bc0-4f67-8a89-1f342e50d5f1',
    "groupings" => [
        [
            "name" => "aaaaa",
            "idnumber" => "aaaa",
            "description" => "<p dir=\"ltr\" style=\"text-align: left;\">aaaa</p>"
        ],
        [
            "name" => "bbbbb",
            "idnumber" => "bbbb",
            "description" => "<p dir=\"ltr\" style=\"text-align: left;\">bbbb</p>"
        ],
        [
            "name" => "ccccc",
            "idnumber" => "cccc",
            "description" => "<p dir=\"ltr\" style=\"text-align: left;\">cccc</p>"
        ],
        [
            "name" => "ddddd",
            "idnumber" => "dddd",
            "description" => "<p dir=\"ltr\" style=\"text-align: left;\">dddd</p>"
        ],
        [
            "name" => "eeeee",
            "idnumber" => "eeee",
            "description" => "<p dir=\"ltr\" style=\"text-align: left;\">eeee</p>"
        ]
    ]
];

$msg = new AMQPMessage(
    json_encode($record),
    array(
        'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
        'type' => 'moodle.dominos.course-groupings-created',
        'timestamp' => time(),
        'delivery_mode' => 2,
        'content_type' => 'application/json'
    )
);


$channel->basic_publish($msg, 'eto');


$channel->close();
$connection->close();
