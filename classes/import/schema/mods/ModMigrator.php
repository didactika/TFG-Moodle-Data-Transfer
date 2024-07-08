<?php

/**
 * Mod class
 *
 * @package     local_data_transfer
 * @category    Importer
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\import\schema\mods;

use local_data_transfer\import\schema\Migrator;

require_once (__DIR__ . '/../../../../../../config.php');

global $CFG;

require_once ("$CFG->dirroot/course/modlib.php");

class ModMigrator extends Migrator
{


    public function create($courseid, object $new_mod, $section)
    {
        $course = parent::get_course_object($courseid);

        $new_mod->visible = 1;
        $new_mod->visibleoncoursepage = 1;
        $new_mod->availabilityconditionsjson = '{"op":"&","c":[],"showc":[]}';
        $new_mod->course = $course->id;
        $new_mod->coursemodule = 0;
        $new_mod->section = $section;
        $new_mod->module = $this->get_mod_id();
        $new_mod->modulename = $this->get_mod_type();
        $new_mod->instance = 0;
        add_moduleinfo($new_mod, $course);
    }

    /**
     * Get Mod type from child class
     * 
     */
    public function get_mod_type(): string
    {
        throw new \Exception("Method 'get_mod_type' must be implemented in the child class");
    }


    /**
     * Get mod id from database
     * 
     */
    public function get_mod_id(): int
    {
        global $DB;
        $modtype = $this->get_mod_type();
        return $DB->get_field('modules', 'id', array('name' => $modtype));
    }

   
}