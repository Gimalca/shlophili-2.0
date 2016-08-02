<?php

class ControllerModuleMenuEditor extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('module/menu_editor');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
        $this->load->model('module/menu_editor');

        $data = array();
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            
            $this->model_setting_setting->editSetting('menu_editor', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_right'] = $this->language->get('text_right');
        $data['text_left'] = $this->language->get('text_left');
        $data['text_please_select'] = $this->language->get('text_please_select');
        $data['text_target_blank'] = $this->language->get('text_target_blank');
        $data['text_target_self'] = $this->language->get('text_target_self');
        $data['text_target_parent'] = $this->language->get('text_target_parent');
        $data['text_target_top'] = $this->language->get('text_target_top');
        
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_href'] = $this->language->get('entry_href');
        $data['entry_position'] = $this->language->get('entry_position');
        $data['entry_preset'] = $this->language->get('entry_preset');
        $data['entry_target'] = $this->language->get('entry_target');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['error_empty_line'] = $this->language->get('error_empty_line');



        if (isset($this->error['error_entries'])) {
            $data['error_entries'] = $this->error['error_entries'];
        } else {
            $data['error_entries'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/menu_editor', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['action'] = $this->url->link('module/menu_editor', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

        // status
        if (isset($this->request->post['menu_editor_enabled'])) {
            $data['menu_editor_enabled'] = $this->request->post['menu_editor_enabled'];
        } else {
            $data['menu_editor_enabled'] = $this->config->get('menu_editor_enabled');
        }

        // entry
        if (isset($this->request->post['menu_editor_entries'])) {
            $data['menu_editor_entries'] = $this->request->post['menu_editor_entries'];
        } else if (is_array($this->config->get('menu_editor_entries'))) {
            $data['menu_editor_entries'] = $this->config->get('menu_editor_entries');
        }else{
            $data['menu_editor_entries'] = array();
        }

        $this->load->model("localisation/language");
        $data['languages'] = $this->model_localisation_language->getLanguages();
        $data['presets'] = $this->model_module_menu_editor->getPresets();



        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('module/menu_editor', $data));
    }

    protected function validate() {

        $entries = $this->request->post['menu_editor_entries'];
        foreach ($entries as $key => $entry) {
            foreach ($entry['names'] as $k => $name) {
                if (empty($name)) {
                    $this->error['error_entries'][$key]['names'][$k] = $this->language->get('error_empty_line');
                }
            }
        }

        return !$this->error;
    }

    public function install() {
        $this->load->model('setting/setting');
        $this->load->model('extension/event');
        $post = array();
        $post['menu_editor_enabled'] = "1";
        $post['menu_editor_entries'] = array();
        $this->model_setting_setting->editSetting('menu_editor', $post);

        $this->model_extension_event->addEvent('menu_editor', 'admin/controller/localisation/language/add/after', 'module/menu_editor/eventUpdatedLanguage');
        $this->model_extension_event->addEvent('menu_editor', 'admin/controller/localisation/language/edit/after', 'module/menu_editor/eventUpdatedLanguage');
        $this->model_extension_event->addEvent('menu_editor', 'admin/controller/localisation/language/delete/after', 'module/menu_editor/eventUpdatedLanguage');
    }

    public function uninstall() {
        $this->load->model('extension/event');
        $this->model_extension_event->deleteEvent('menu_editor');
    }

    public function eventUpdatedLanguage() {
        $this->load->model('setting/setting');
        $this->load->model("localisation/language");
        $languages = $this->model_localisation_language->getLanguages();
        $menu_editor_entries = $this->config->get('menu_editor_entries');

        foreach ($menu_editor_entries as $key => $menu_editor_entry) {
            $names = array();
            foreach ($languages as $language) {
                if (isset($menu_editor_entry['names'][$language['language_id']])) {
                    $names[$language['language_id']] = $menu_editor_entry['names'][$language['language_id']];
                } else {
                    $names[$language['language_id']] = $menu_editor_entry['names'][$this->getDefaultLanguageId($languages)];
                }
            }
            $menu_editor_entries[$key]['names'] = $names;
        }
        $post['menu_editor_enabled'] = $this->config->get('menu_editor_enabled');
        $post['menu_editor_entries'] = $menu_editor_entries;
        $this->model_setting_setting->editSetting('menu_editor', $post);
    }

    private function getDefaultLanguageId($languages) {
        $language_code = $this->config->get('config_language');
        $language_id = 1;
        foreach ($languages as $language) {
            $language_id = $language['language_id'];
            break;
        }
        foreach ($languages as $language) {
            if ($language['code'] == $language_code) {
                $language_id = $language['language_id'];
            }
        }
        return $language_id;
    }

}
