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
    public string $name;
    public int $visible;
    public string $availability;
    public array $modules;
    
    /**
     * Constructor
     * 
     * @param int $courseid
     * @param bool $include_mods
     */
    public function __construct(int $id, int $section , string $name, int $visible, string $availability)
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
     */
    public function get_schema(): array
    {
        return [
            'id' => $this->id,
            'section' => $this->section,
            'name' => $this->name,
            'visible' => $this->visible,
            'availability' => $this->availability,
            'modules' => $this->modules
        ];
    }
}