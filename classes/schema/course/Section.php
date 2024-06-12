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

 /**
  * Course section class  
  */
class Section{

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
    public function __construct(int $id, int $section, string $name = null, int $visible, ?string $availability = null)
    {
        global $DB;
        $this->DB = $DB;
        $this->id = $id;
        $this->section = $section;
        $this->name = $name;
        $this->visible = $visible;
        $this->availability = $availability;
        $this->modules = [];
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
            $schema['modules'] = $this->modules;
        }

        return $schema;

    }
}