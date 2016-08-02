<?php

class ModelModuleMenuEditor extends Model {

    public function getMenuContent($side = 0,$language_id) {
        $q = $this->db->query("SELECT * FROM `" . DB_PREFIX . "special_label` WHERE language_id = $language_id");
        return $q->row['label'];
    }

  

}
