<?php

/**
 * Mod forum migrator
 *
 * @package     local_data_transfer
 * @category    Importer
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_data_transfer\import\schema\mods;

class ModForum extends ModMigrator
{
    public $modType = 'forum';
    public $recordid;
    public $new_mod;
    public $errors = [];

    public function __construct($mod_data, $recordid)
    {
        $this->recordid = $recordid;
        $this->new_mod = (object) $mod_data;
        $this->validate_new_mod($this->new_mod);
    }

    public function get_mod_type(): string
    {
        return $this->modType;
    }

    public function add_error(string $error): void
    {
        $this->errors[] = $error;
    }

    public function get_errors(): array
    {
        return $this->errors;
    }

    public function validate_new_mod(object $new_mod)
    {
        if (!isset($new_mod->name)) {
            $this->add_error("Mod name is required");
        }
        if (!isset($new_mod->section)) {
            $this->add_error("Mod section is required");
        }
        if (!isset($new_mod->intro)) {
            $new_mod->intro = '';
        }

    }

    public function create($courseid, $new_mod, $section)
    {
        parent::create($courseid, $new_mod, $section);
        $this->success($courseid);
    }


    /**
     * Mark course creation as successful
     * 
     * @param int $courseid ID of the created course
     * @return void
     */
    public function success($courseid): void
    {
        global $DB;
        $DB->delete_records('transfer_pending_commands', ['id' => $this->recordid]);

        parent::success_creation("Mod forum created successfully", ['courseid' => $courseid]);

        echo "[+] Mod forum created in course id: {$courseid} \n";
    }

}
