<?php

/**
 * Services definition
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\schema\course;

use stdClass;

/**
 * Course content class
 */
class Content
{
    public stdClass $schema;
    private array $sections = [];
    private int $courseid;
    private $DB;
    private bool $include_mods;

    /**
     * Constructor
     * 
     * @param int $courseid
     * @param bool $include_mods
     */
    public function __construct(int $courseid, bool $include_mods = true)
    {
        global $DB;
        $this->DB = $DB;
        $this->courseid = $courseid;
        $this->schema = new stdClass();
        $this->include_mods = $include_mods;
        $this->set_content();
    }

    /**
     * Set the course content
     * 
     * @return void
     */
    private function set_content(): void
    {
        $this->sections = $this->get_sections();
        if ($this->include_mods) {
            $this->set_mods();
        }
    }

    /**
     * Get the course sections
     * 
     * @return array
     */
    private function get_sections(): array
    {
        $sections = $this->DB->get_records(
            'course_sections',
            ['course' => $this->courseid],
            'section',
            'id, section, name, visible, availability'
        );

        return array_map(function ($section) {
            return new Section(
                $section->id,
                $section->section,
                $section->name,
                $section->visible,
                $section->availability
            );
        }, $sections);
    }

    /**
     * Set the modules for each section (Placeholder for future implementation)
     * 
     * @return void
     */
    private function set_mods(): void
    {
        // TODO: Implement the method to include modules in each section.
    }

    /**
     * Get the JSON schema
     * 
     * @return string
     */
    public function get_json_schema(): string
    {
        $this->schema->sections = array_map(function ($section) {
            return $section->get_schema();
        }, $this->sections);

        return json_encode($this->schema);
    }
}