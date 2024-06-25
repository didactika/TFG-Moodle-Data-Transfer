<?php

/**
 * Importer class
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\import;

class EventController
{

    private $courseuuid;

    private $event_schema;

    private Course $course;

    public function __construct(string $event_schema)
    {
        $this->event_schema = self::convert_to_object($event_schema);
        $this->courseuuid =  $this->event_schema->courseuuid;
        $this->course = new Course();
    }

    private static function convert_to_object(string $event_schema): object
    {
        return json_decode($event_schema);
    }

    public function event_new_course()
    {
        $this->course::save_to_process_course($this->courseuuid, $this->event_schema);    
    }

    // public function event_new_groups(){
    //     $this->course::save_create_groups($this->courseuuid, $this->event_schema);
    // }

}
