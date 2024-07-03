<?php

/**
 * Importer class
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\import\events;

use stdClass;

class EventController
{
    /**
     * Saves a message to the pending commands table.
     * 
     * @param int $type The type of the message.
     * @param object $body The body of the message.
     * @return int The id of the newly created record.
     * 
     */
    public static function save_to_pending_commands(int $type, array $body): int
    {
        global $DB;

        // TODO Validate type and body before saving
        $record = new stdClass();
        $record->type = $type;
        $record->jsondata = json_encode($body);
        $record->timecreated = date('Y-m-d H:i:s', time());
        $record->timemodified = date('Y-m-d H:i:s', time());

        if (!$id = $DB->insert_record('transfer_pending_commands', $record, false)) {
            return 0;
        }

        return $id;
    }
}
