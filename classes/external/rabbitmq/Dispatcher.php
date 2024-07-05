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

use local_data_transfer\Constants as GlobalConstants;
use local_data_transfer\import\events\EventController;

/**
 * Class Dispatcher
 *
 * A class responsible for dispatching and validating messages.
 */
class Dispatcher
{
    /**
     * Callback dispatcher method.
     *
     * Processes a message and validates its properties before dispatching.
     *
     * @param object $msg The message object to process.
     * @return void
     */
    public static function callback_dispatcher(object $msg): void
    {
        $body = $msg->getBody();
        $properties = $msg->get_properties();

        if (!(self::validate_properties($properties))) {
            // TODO: Handle validation errors
            return;
        }
        self::dispatcher($properties, json_decode($body, true));
        $msg->ack();
    }

    /**
     * Validates the properties of a message.
     *
     * @param array $properties The properties of the message to validate.
     * @return bool True if all required properties are present and valid, false otherwise.
     */
    private static function validate_properties(array $properties): bool
    {
        $required_keys = ['timestamp', 'type', 'content_type'];

        foreach ($required_keys as $key) {
            if (!array_key_exists($key, $properties)) {
                echo "Error: Missing key '$key' on message.\n";
                return false;
            }
        }

        if (!isset($properties['content_type']) || trim(strtolower($properties['content_type'])) !== 'application/json') {
            echo "Error: The content must have content_type 'application/json'.\n";
            return false;
        }

        return true;
    }

    /**
     * Dispatcher method.
     *
     * Placeholder method for actual dispatching logic based on validated properties and message body.
     *
     * @param array $properties The validated properties of the message.
     * @param mixed $body The body of the message.
     * @return void
     */
    private static function dispatcher(array $properties, array $body): void
    {
        $type = $properties['type'];
        $appid = get_config('local_data_transfer', 'external_appid');
        
        switch ($type) {
            case $appid . ".course-base-created":
                echo " [+] EVENT: COURSE-BASE-CREATED PROCESSING\n";
                EventController::save_to_pending_commands(GlobalConstants::EVENT_TYPES["COURSE_BASE_CREATED"], $body);
                break;
            case $appid . ".course-section-created":
                echo " [+] EVENT: COURSE_SECTION_CREATED PROCESSING\n";
                EventController::save_to_pending_commands(GlobalConstants::EVENT_TYPES["COURSE_SECTION_CREATED"], $body);
                break;
            case $appid . ".course-groups-created":
                echo " [+] EVENT: COURSE_GROUPS_CREATED PROCESSING\n";
                EventController::save_to_pending_commands(GlobalConstants::EVENT_TYPES["COURSE_GROUPS_CREATED"], $body);
                break;
            case $appid . ".course-groupings-created":
                echo " [+] EVENT: COURSE_GROUPINGS_CREATED PROCESSING\n";
                EventController::save_to_pending_commands(GlobalConstants::EVENT_TYPES["COURSE_GROUPINGS_CREATED"], $body);
                break;
            default:
                break;
        }
    }
}
