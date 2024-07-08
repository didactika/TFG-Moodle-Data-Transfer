<?php

/**
 * Migrator class
 *
 * @package     local_data_transfer
 * @category    Importer
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\import\schema;

use local_data_transfer\import\events\PublisherController;

/**
 * Class Migrator
 *
 * A class responsible for containing the logic to migrate objects
 */

class Migrator
{
    /**
     * Get the course ID from the database
     * 
     * @param string $uuid
     * @return void
     */
    protected function get_courseid(string $uuid): void
    {
        global $DB;

        $sql = "SELECT * FROM {transfer_course_created} WHERE " . $DB->sql_compare_text('migrationuuid') . " = " . $DB->sql_compare_text(':uuid');
        $params = ['uuid' => $uuid];

        $course = $DB->get_record_sql($sql, $params);

        if ($course) {
            $this->set_courseid($course->courseid);
        } else {
            $this->add_error('Course not found in the database');
        }
    }

    /**
     * Set the course ID (to be implemented in the child class)
     *
     * @param int $courseid
     */
    protected function set_courseid(int $courseid): void
    {
        // This method should be implemented in the child class 
        throw new \Exception("Method 'set_courseid' must be implemented in the child class");
    }

    /**
     * Add an error message (to be implemented in the child class)
     *
     * @param string $error
     */
    protected function add_error(string $error): void
    {
        // This method should be implemented in the child class
        throw new \Exception("Method 'add_error' must be implemented in the child class");
    }


    /**
     * Mark course creation as failed
     * 
     * @return void
     */
    protected function fail_creation($message = "Something went wrong", $errors = [], $data = []): void
    {
        $publisher = new PublisherController();
        $publisher->error_message($errors, $message, $data);
    }

    /**
     * Mark course creation as successful
     * 
     * @param string $message Success message
     * @param array $data Additional data
     * @return void
     */
    protected function success_creation(string $message = "Event created successfully", array $data = []): void
    {
        $publisher = new PublisherController();
        $publisher->success_message($data, $message);
    }


    /**
     * Get course object
     * 
     * @param int $courseid courseud
     * @return object
     */
    protected function get_course_object($courseid){
        global $DB;
        return $DB->get_record('course', array('id' => $courseid));
    }
}
