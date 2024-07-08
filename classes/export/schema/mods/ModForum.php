<?php

/**
 * 
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 namespace local_data_transfer\export\schema\mods;

 class ModForum {
    
    public $instanceid;
    public $name;
    public $intro;
    public $modtype;
    

    /**
     * Constructor
     */
    public function __construct($instanceid)
    {
        $this->instanceid = $instanceid;
        $this->modtype = 'forum';
    }

    /**
     * Set the content of the mod
     */
    public function set_content()
    {
        global $DB;
        $mod_data = $DB->get_record($this->modtype, ['id' => $this->instanceid], '*', MUST_EXIST);
        $this->name = $mod_data->name;
        $this->intro = $mod_data->intro;
    }   

    /**
     * Get the mod data
     */
    public function get_data()
    {
        $this->set_content();
        return [
            'modtype' => $this->modtype,
            'instanceid' => $this->instanceid,
            'name' => $this->name,
            'intro' => $this->intro,
        ];
    }
 }