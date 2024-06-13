<?php

/**
 * Course class
 *
 * @package     local_data_transfer
 * @category    Importer
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\import;

use local_data_transfer\schema\course\Validator;

// require moodle config
require_once(__DIR__ . '/../../../../config.php');

/**
 * Course class
 * 
 * This class represents a course in Moodle
 * Handles the course data to be exported/imported
 */

class Course
{

    public static function save_to_process_course($schema){
        global $DB;
        try {
            Validator::validate_header($schema);
            

        } catch (\Throwable $th) {
            $msg = $th->getMessage();
            $publisher = new PublisherController();
            $publisher->publish_creating_error_message($msg);
        }
    }

}
