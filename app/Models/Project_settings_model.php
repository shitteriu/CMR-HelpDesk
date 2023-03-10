<?php

namespace App\Models;

class Project_settings_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'project_settings';
        parent::__construct($this->table);
    }

    function get_setting($project_id, $setting_name) {
        $result = $this->db_builder->getWhere(array('project_id' => $project_id, 'setting_name' => $setting_name), 1);
        if (count($result->getResult()) == 1) {
            return $result->getRow()->setting_value;
        }
    }

    function save_setting($project_id, $setting_name, $setting_value) {
        $fields = array(
            'project_id' => $project_id,
            'setting_name' => $setting_name,
            'setting_value' => $setting_value
        );

        $exists = $this->get_setting($project_id, $setting_name);
        if ($exists === NULL) {
            return $this->db_builder->insert($fields);
        } else {
            $this->db_builder->where('setting_name', $setting_name);
            $this->db_builder->where('project_id', $project_id);
            $this->db_builder->update($fields);
        }
    }

    function get_details($options = array()) {
        $project_settings_table = $this->db->prefixTable('project_settings');

        $where = "";
        $project_id = $this->_get_clean_value($options, "project_id");
        if ($project_id) {
            $where = " WHERE project_id=$project_id";
        }

        $sql = "SELECT * FROM $project_settings_table $where";
        return $this->db->query($sql);
    }

}
