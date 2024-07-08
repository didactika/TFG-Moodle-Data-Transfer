<?php

/**
 * Core course class
 *
 * @package     local_data_transfer
 * @category    services
 * @copyright   Franklin López
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 namespace local_data_transfer\export\schema\mods;

 class ModUrl {
    
    public $instanceid;
    public $name;
    public $externalurl;
    public $intro;
    public $display;
    public $modtype;
    

    /**
     * Constructor
     */
    public function __construct($instanceid)
    {
        $this->instanceid = $instanceid;
        $this->modtype = 'url';
    }

    /**
     * Set the content of the mod
     */
    public function set_content()
    {
        global $DB;
        $mod_data = $DB->get_record($this->modtype, ['id' => $this->instanceid], '*', MUST_EXIST);
        $this->name = $mod_data->name;
        $this->externalurl = $mod_data->externalurl;
        $this->intro = $mod_data->intro;
        $this->display = $mod_data->display;
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
            'externalurl' => $this->externalurl,
            'intro' => $this->intro,
            'display' => $this->display
        ];
    }
 }