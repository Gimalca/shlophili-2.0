<?php

class ModelModuleMenuEditor extends Model {

    public function getPresets() {
        $presets = array();
        $presets[] = array("text" => "Home", "value" => HTTP_CATALOG,"names"=>array("1"=>"Home","2"=>"Home"));
        $this->load->model("catalog/information");
        $informations = $this->model_catalog_information->getInformations();
        
        
        foreach ($informations as $information) {
            $presets[] = array(
                "text" => $information['title'], 
                "names" => $this->getInformationName($information['information_id']), 
                "value" => $this->getInformationUrl($information['information_id']));
        }
        return $presets;
        
    }

    private function getInformationUrl($information_id){
        
        $q = $this->db->query("SELECT DISTINCT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=" . (int)$information_id . "'");
        
        return ($this->config->get('config_seo_url') && !empty($q->row['keyword']))?$q->row['keyword']:"index.php?route=information/information&information_id=$information_id";
    }
    private function getInformationName($information_id){
        
        $q = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_description as a "
                . "LEFT JOIN (SELECT language_id FROM " . DB_PREFIX . "language WHERE status = 1) as b "
                . "ON a.language_id = b.language_id "
                . "WHERE information_id = " . (int)$information_id . "");
        $names = array();
        foreach ($q->rows as $row) {
            $names[$row['language_id']] = $row['title'];
            
        }
        
        return $names;
    }
    
}
