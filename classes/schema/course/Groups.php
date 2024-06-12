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
 * Course groups class
 */
class Groups
{
    private $DB;
    public int $courseid;
    public array $groups = [];

    /**
     * Constructor
     * 
     * @param int $courseid Course ID
     */
    public function __construct(int $courseid)
    {
        global $DB;
        $this->DB = $DB;
        $this->courseid = $courseid;
        $this->set_groups();
    }

    /**
     * Set the course groups
     */
    private function set_groups()
    {
        $groups = $this->DB->get_records('groups', ['courseid' => $this->courseid]);
        foreach ($groups as $group) {
            $this->groups[] = [
                'id' => $group->id,
                'name' => $group->name,
                'idnumber' => $group->idnumber,
                'description' => $group->description,
            ];
        }
    }

    /**
     * Get the course groups
     * 
     * @return array The course groups
     */
    public function get_groups(): array
    {
        return $this->groups;
    }
}
