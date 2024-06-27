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

/**
 * Course groupings class
 */
class Groupings
{
    private $DB;
    public int $courseid;
    public array $groupings = [];

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
        $this->set_groupings();
    }

    /**
     * Set the course groupings with relation of wich groups are in each grouping
     */
    private function set_groupings()
    {
        $groupings = $this->DB->get_records('groupings', ['courseid' => $this->courseid]);
        foreach ($groupings as $grouping) {
            $grouping->groups = $this->DB->get_records('groupings_groups', ['groupingid' => $grouping->id]);
            $groups = [];
            foreach ($grouping->groups as $group) {
                $groups[] = $group->groupid;
            }
            $this->groupings[] = [
                'id' => $grouping->id,
                'name' => $grouping->name,
                'description' => $grouping->description,
                'groups' => $groups,
            ];
        }
    }

    /**
     * Get the course groupings
     * 
     * @return array The course groupings
     */
    public function get_groupings(): array
    {
        return $this->groupings;
    }
}
