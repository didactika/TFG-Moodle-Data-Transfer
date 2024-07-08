<?php

/**
 * Services definition
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\export\schema;

use local_data_transfer\export\schema\mods\ModForum;
use local_data_transfer\export\schema\mods\ModUrl;

/**
 * Course section class  
 */
class Section
{

    public $DB;
    public int $id;
    public int $section;
    public ?string $name;
    public int $visible;
    public ?string $availability;
    public array $modules;

    /**
     * Constructor
     * 
     * @param int $courseid
     * @param bool $include_mods
     */
    public function __construct(int $id)
    {
        global $DB;
        $this->DB = $DB;
        $this->id = $id;
        $this->modules = [];
        $this->set_content();
    }

    /**
     * Set the section content
     */
    public function set_content()
    {
        if ($this->verify_if_section_exist() === false) {
            throw new \Exception('Section does not exist');
        }
        $section_data = $this->DB->get_record('course_sections', ['id' => $this->id], '*', MUST_EXIST);
        $this->section = $section_data->section;
        $this->name = $section_data->name;
        $this->visible = $section_data->visible;
        $this->availability = $section_data->availability;
    }

    /**
     * Verify if the section exists
     * 
     * @return bool
     */
    private function verify_if_section_exist(): bool
    {
        return $this->DB->record_exists('course_sections', ['id' => $this->id]);
    }

    /**
     * Set the modules
     */
    private function set_modules()
    {
        $mods = $this->DB->get_records('course_modules', ['section' => $this->id]);
        foreach ($mods as $mod) {
            $modType = $this->DB->get_field('modules', 'name', ['id' => $mod->module]);
            if($modType === 'url') {
                $this->modules[] = (new ModUrl($mod->instance))->get_data() ;
            }
            if($modType === 'forum') {
                $this->modules[] = (new ModForum($mod->instance))->get_data() ;
            }
        }
    }

    /**
     * Get the schema
     * 
     * @param bool $include_mods Include the modules in return schema
     */
    public function get_schema($include_mods): array
    {
        $schema = [
            'id' => $this->id,
            'section' => $this->section,
            'name' => $this->name,
            'visible' => $this->visible,
            'availability' => $this->availability,
            'modules' => $this->modules
        ];

        if ($include_mods) {
            $this->set_modules();
            $schema['modules'] = $this->modules;
        }
        return $schema;
    }
}
