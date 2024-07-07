<?php

/**
 * Mod class
 *
 * @package     local_data_transfer
 * @category    Importer
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\import\schema\mods;

use local_data_transfer\import\schmea\Migrator;

require_once (__DIR__ . '/../../../../../../config.php');

global $CFG;

require_once ("$CFG->dirroot/course/modlib.php");

class ModMigrator extends Migrator
{

    public function create($courseid, object $new_mod)
    {
        $course = parent::get_course_object($courseid);

        $new_mod->visible = 1;
        $new_mod->visibleoncoursepage = 1;
        $new_mod->availabilityconditionsjson = '{"op":"&","c":[],"showc":[]}';
        $new_mod->completionunlocked = 0;
        $new_mod->completion = "2";
        $new_mod->completionview = "1";
        $new_mod->completionexpected = 0;
        $new_mod->tags = array();
        $new_mod->course = $course->id;
        $new_mod->coursemodule = 0;
        $new_mod->section = $section;
        $new_mod->module = $moduleID;
        $new_mod->modulename = 'url';
        $new_mod->instance = 0;
        $new_mod->add = 'url';
        $new_mod->update = 0;
        $new_mod->return = 0;
        $new_mod->sr = 0;
        $new_mod->competencies = array();
        $new_mod->competency_rule = "0";
        $new_mod->submitbutton2 = "Save";
        $new_mod->showdescription = "0";
        $new_mod->display = "6"; //0 for normal, 1 for embed, 5 for open, 6 for popup
        $new_mod->popupwidth = "1200";
        $new_mod->popupheight = "800";
        $new_mod->name = $name;
        $new_mod->externalurl = $url;

        add_moduleinfo($new_mod, $course);
    }

    /**
     * Validate required creation object
     */
    public function validate_new_mod(object $new_mod) {
        //TODO
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
        $modtype = get_mod_type();
        return $DB->get_field('modules', 'id', array('name' => $modtype));
    }

   
}