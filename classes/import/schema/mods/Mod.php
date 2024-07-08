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

class Mod extends Migrator
{

    public int $recordid;
    public string $uuid = '';
    public int $courseid;
    public array $errors = [];
    public ?ModUrl $modurl = null;
    public ?ModForum $modforum = null;

    /**
     * Constructor
     * 
     * @param int $recordid ID of the record
     * @param string $json JSON string containing course data
     */
    public function __construct(int $recordid, string $json)
    {
        $this->recordid = $recordid;
        $this->set_from_json($json);
        if ($this->uuid) {
            $this->get_courseid($this->uuid);
        }
    }


    public function set_from_json(string $json): void
    {
        $data = json_decode($json, true)['data'];
        
        if (!isset($data['uuid'])) {
            $this->add_error('Course uuid is not set');
        } else {
            $this->uuid = $data['uuid'];
        }

        if (!isset($data['modtype'])) {
            $this->add_error('Mod type is not set');
        } else {
            $modtype = $data['modtype'];
            switch ($modtype) {
                case 'url':
                    $this->modurl = new ModUrl($data, $this->recordid);
                    break;
                case 'forum':
                    $this->modforum = new ModForum($data, $this->recordid);
                    break;
                default:
                    $this->add_error('Mod type not supported');
            }
        }
    }
    
     /**
     * Implement the method to set the course ID
     *
     * @param int $courseid
     */
    protected function set_courseid(int $courseid): void
    {
        $this->courseid = $courseid;
    }

    /**
     * Implement the method to add an error message
     *
     * @param string $error
     */
    protected function add_error(string $error): void
    {
        $this->errors[] = $error;
    }


    public function get_errors(): array
    {
        return $this->errors;
    }

    public function get_recordid(): int
    {
        return $this->recordid;
    }

    public function create_mod(){
        if ($this->modurl) {
            if (!empty($this->modurl->get_errors()) || !empty($this->get_errors())) {
                echo "Errors found in mod creation url\n";
                return;
            }
            $this->modurl->create($this->courseid, $this->modurl->new_mod, $this->modurl->new_mod->section);
        }
        if ($this->modforum) {
            if (!empty($this->modforum->get_errors()) || !empty($this->get_errors())) {
                echo "Errors found in mod creation forum\n";
                return;
            }
            $this->modforum->create($this->courseid, $this->modforum->new_mod, $this->modforum->new_mod->section);
        }
    }


}