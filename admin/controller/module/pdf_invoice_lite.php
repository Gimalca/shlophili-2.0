<?php
class ControllerModulePdfInvoiceLite extends Controller {
	private $error = array(); 
  private $OC_V2; 
  private $OC_V21;
  private $OC_V21X;
  private $OC_V22X;
	
	public function __construct($registry) {
		$this->OC_V2 = substr(VERSION, 0, 1) == 2;
    $this->OC_V21 = substr(VERSION, 0, 3) == '2.1';
    $this->OC_V21X = version_compare(VERSION, '2.1', '>=');
    $this->OC_V22X = version_compare(VERSION, '2.2', '>=');
		parent::__construct($registry);
	}

	public function index() {
		
		$data['_language'] = &$this->language;
		$data['_config'] = &$this->config;
		$data['_url'] = &$this->url;
		$data['token'] = $this->session->data['token'];
    $data['OC_V2'] = $this->OC_V2;
 
		$this->language->load('module/pdf_invoice_lite');

		if (!$this->OC_V2) {
			$this->document->addStyle('view/pdf_invoice_pro/awesome/css/font-awesome.min.css');
			$this->document->addStyle('view/pdf_invoice_pro/bootstrap.min.css');
			$this->document->addStyle('view/pdf_invoice_pro/bootstrap-theme.min.css');
			$this->document->addScript('view/pdf_invoice_pro/bootstrap.min.js');
		} else {
			$this->document->addScript('view/pdf_invoice_pro/jquery-ui.min.js');
		}
		$this->document->addScript('view/pdf_invoice_pro/jqueryFileTree.js');
		$this->document->addScript('view/pdf_invoice_pro/spectrum.js');
		$this->document->addScript('view/pdf_invoice_pro/itoggle.js');
		$this->document->addStyle('view/pdf_invoice_pro/jqueryFileTree.css');
		$this->document->addStyle('view/pdf_invoice_pro/spectrum.css');
		$this->document->addStyle('view/pdf_invoice_pro/style.css');
		
		$this->document->setTitle(strip_tags($this->language->get('heading_title')));
		
		// check tables
		$this->db_tables();
		$this->load->model('setting/setting');
		
		$data['store_id'] = $store_id = 0;

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			if (isset($_POST['customer_groups'])){
				foreach ($_POST['customer_groups'] as $groupid => $group) { 
					$this->db->query("UPDATE " . DB_PREFIX . "customer_group SET company_id_display = '" . isset($group['company_id_display']) . "', company_id_required = '" . isset($group['company_id_required']) . "', tax_id_display = '" . isset($group['tax_id_display']) . "', tax_id_required = '" . isset($group['tax_id_required']) . "' WHERE customer_group_id = '" . (int)$groupid . "'");
				}
			}
			
			$this->model_setting_setting->editSetting('pdf_invoice', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
									
			if ($this->OC_V2) {
				$this->response->redirect($this->url->link('module/pdf_invoice_lite', 'token=' . $this->session->data['token'], 'SSL'));
			} else {
				$this->redirect($this->url->link('module/pdf_invoice_lite', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}
		
		if (is_file(DIR_SYSTEM.'../vqmod/xml/pdf_invoice_lite.xml')) {
			$data['module_version'] = simplexml_load_file(DIR_SYSTEM.'../vqmod/xml/pdf_invoice_lite.xml')->version;
      $data['module_type'] = 'vqmod';
		} else if (is_file(DIR_SYSTEM.'../system/pdf_invoice_lite.ocmod.xml')) {
      $data['module_version'] = simplexml_load_file(DIR_SYSTEM.'../system/pdf_invoice_lite.ocmod.xml')->version;
      $data['module_type'] = 'ocmod';
		} else {
			$data['module_version'] = 'not found';
      $data['module_type'] = '';
		}
		
		$this->load->model('localisation/language');
		$data['languages'] = $languages = $this->model_localisation_language->getLanguages();
		
    foreach ($data['languages'] as &$tpl_lng) {
      if ($this->OC_V22X) {
        $tpl_lng['image'] = 'language/'.$tpl_lng['code'].'/'.$tpl_lng['code'].'.png';
      } else {
        $tpl_lng['image'] = 'view/image/flags/'. $tpl_lng['image'];
      }
    }
		
		// module checks
    if (is_file(DIR_SYSTEM.'../vqmod/xml/pdf_invoice_lite.xml') && is_file(DIR_SYSTEM.'../system/pdf_invoice_lite.ocmod.xml')) {
      $this->error['warning'] = 'Warning : both vqmod and ocmod version are installed<br/>- delete /vqmod/xml/pdf_invoice_lite.xml if you want to use ocmod version<br/>- or delete /system/pdf_invoice_lite.ocmod.xml if you want to use vqmod version';
    }

		if (!is_file(DIR_SYSTEM . 'library/mpdf/mpdf.php')) {
			$this->error['warning'] = 'Mpdf library not detected, please download the libraries package and upload it';
    }
    
		foreach ($languages as $language) {
      $lg_folder = !empty($language['directory']) ? $language['directory'] : $language['code'];
      
			if (!is_file(DIR_SYSTEM . '../catalog/language/' . $lg_folder . '/module/pdf_invoice.php')) {
				$this->error['warning'] = 'Language file missing for '.$language['name'] .' language, please follow this procedure : <br />- Check if this language is included in the module package, in <b>extra languages/</b> folder<br />- If it doesn\'t exist, just copy the english file and open it to translate it.<br />- Copy <b>pdf_invoice.php</b> language file into <b>/catalog/language/'.$lg_folder.'/module/</b>';
      }
		}
		// end module checks
		
		$data['heading_title'] = strip_tags($this->language->get('heading_title'));
		
	    // Mijoshop specific
	    if (defined('_JEXEC')) {
	       $data['button_savenew'] = $this->language->get('button_savenew');
				 $data['button_saveclose'] = $this->language->get('button_saveclose');
	    }
		
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['token'] = $this->session->data['token'];
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->session->data['error'])) {
			$data['error'] = $this->session->data['error'];
		
			unset($this->session->data['error']);
		} else {
			$data['error'] = '';
		}
		
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => strip_tags($this->language->get('heading_title')),
			'href'      => $this->url->link('module/pdf_invoice_lite', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link('module/pdf_invoice_lite', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['modules'] = array();
		
		// customer groups
    if ($this->OC_V21X) {
      $this->load->model('customer/customer_group');
      $data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

    } else {
		  $this->load->model('sale/customer_group');
		  $data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
    }
		
		$data['group_settings'] = false;
		if ($this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "customer_group` LIKE 'tax_id_display'")->row) {
			$data['group_settings'] = true;
		}
		
		/* gestion des variables */
		
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$data['icons'] = array_diff(scandir(DIR_IMAGE . 'invoice'), array('..', '.'));
		
		// Tab 1 - main settings
		if (isset($this->request->post['pdf_invoice_mail'])) {
			$data['pdf_invoice_mail'] = $this->request->post['pdf_invoice_mail'] ? true : false;
		} else {
			$data['pdf_invoice_mail'] = $this->config->get('pdf_invoice_mail');
		}
		
		if (isset($this->request->post['pdf_invoice_adminalertcopy'])) {
			$data['pdf_invoice_adminalertcopy'] = $this->request->post['pdf_invoice_adminalertcopy'] ? true : false;
		} else {
			$data['pdf_invoice_adminalertcopy'] = $this->config->get('pdf_invoice_adminalertcopy');
		}
		
		if (isset($this->request->post['pdf_invoice_admincopy'])) {
			$data['pdf_invoice_admincopy'] = $this->request->post['pdf_invoice_admincopy'] ? true : false;
		} else {
			$data['pdf_invoice_admincopy'] = $this->config->get('pdf_invoice_admincopy');
		}
		
		if (isset($this->request->post['pdf_invoice_invoiced'])) {
			$data['pdf_invoice_invoiced'] = $this->request->post['pdf_invoice_invoiced'] ? true : false;
		} else {
			$data['pdf_invoice_invoiced'] = $this->config->get('pdf_invoice_invoiced');
		}
		
		if (isset($this->request->post['pdf_invoice_vat_number'])) {
			$data['pdf_invoice_vat_number'] = $this->request->post['pdf_invoice_vat_number'];
		} else {
			$data['pdf_invoice_vat_number'] = $this->config->get('pdf_invoice_vat_number');
		}
		
		if (isset($this->request->post['pdf_invoice_company_id'])) {
			$data['pdf_invoice_company_id'] = $this->request->post['pdf_invoice_company_id'];
		} else {
			$data['pdf_invoice_company_id'] = $this->config->get('pdf_invoice_company_id');
		}
		
    $data['custom_fields'] = false;
    if ($this->OC_V2) {
      if ($this->OC_V21X) {
        $this->load->model('customer/custom_field');
        $data['custom_fields'] = $this->model_customer_custom_field->getCustomFields();
      } else {
        $this->load->model('sale/custom_field');
        $data['custom_fields'] = $this->model_sale_custom_field->getCustomFields();
    }
      
    foreach($data['custom_fields'] as $k => $custom_field) {
      if(!$custom_field['status']) {
        unset( $data['custom_fields'][$k]);
      }
    }
    }
    
		if (isset($this->request->post['pdf_invoice_tax'])) {
			$data['pdf_invoice_tax'] = $this->request->post['pdf_invoice_tax'] ? true : false;
		} else {
			$data['pdf_invoice_tax'] = $this->config->get('pdf_invoice_tax');
		}
		
		if (isset($this->request->post['pdf_invoice_total_tax'])) {
			$data['pdf_invoice_total_tax'] = $this->request->post['pdf_invoice_total_tax'] ? true : false;
		} else {
			$data['pdf_invoice_total_tax'] = $this->config->get('pdf_invoice_total_tax');
		}
		
		if (isset($this->request->post['pdf_invoice_customerid'])) {
			$data['pdf_invoice_customerid'] = $this->request->post['pdf_invoice_customerid'] ? true : false;
		} else {
			$data['pdf_invoice_customerid'] = $this->config->get('pdf_invoice_customerid');
		}
		
		if (isset($this->request->post['pdf_invoice_customerprefix'])) {
      		$data['pdf_invoice_customerprefix'] = $this->request->post['pdf_invoice_customerprefix'];
    	} else { 
			$data['pdf_invoice_customerprefix'] = $this->config->get('pdf_invoice_customerprefix');
		}
		
		if (isset($this->request->post['pdf_invoice_customersize'])) {
      		$data['pdf_invoice_customersize'] = $this->request->post['pdf_invoice_customersize'];
    	} else { 
			$data['pdf_invoice_customersize'] = $this->config->get('pdf_invoice_customersize');
		}
		
		if (isset($this->request->post['pdf_invoice_auto_notify'])) {
      		$data['pdf_invoice_auto_notify'] = $this->request->post['pdf_invoice_auto_notify'];
    	} else { 
			$data['pdf_invoice_auto_notify'] = $this->config->get('pdf_invoice_auto_notify');
		}
		
		if (isset($this->request->post['pdf_invoice_duedate'])) {
			$data['pdf_invoice_duedate'] = $this->request->post['pdf_invoice_duedate'];
		} else {
			$data['pdf_invoice_duedate'] = $this->config->get('pdf_invoice_duedate');
		}
		
		if (isset($this->request->post['pdf_invoice_adminlang'])) {
			$data['pdf_invoice_adminlang'] = $this->request->post['pdf_invoice_adminlang'];
		} else {
			$data['pdf_invoice_adminlang'] = $this->config->get('pdf_invoice_adminlang');
		}
		
		if (isset($this->request->post['pdf_invoice_icon'])) {
			$data['pdf_invoice_icon'] = $this->request->post['pdf_invoice_icon'];
		} else {
			$data['pdf_invoice_icon'] = $this->config->get('pdf_invoice_icon');
		}
		
		// Tab 2 - invoice settings
		$this->load->model('tool/image');
		
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 200, 60);
		
		if (isset($this->request->post['pdf_invoice_logo'])) {
			$data['pdf_invoice_logo'] = $this->request->post['pdf_invoice_logo'];
		} else {
			$data['pdf_invoice_logo'] = $this->config->get('pdf_invoice_logo');
		}
		
		if (isset($this->request->post['pdf_invoice_logo']) && file_exists(DIR_IMAGE . $this->request->post['pdf_invoice_logo'])) {
			$data['thumb_header'] = $this->model_tool_image->resize($this->request->post['pdf_invoice_logo'], 200, 60);
		} elseif ($this->config->get('pdf_invoice_logo') && file_exists(DIR_IMAGE . $this->config->get('pdf_invoice_logo'))) {
			$data['thumb_header'] = $this->model_tool_image->resize($this->config->get('pdf_invoice_logo'), 200, 60);
		} else {
			if($this->OC_V2) {
				$data['thumb_header'] = $this->model_tool_image->resize('no_image.png', 200, 60);
			} else {
				$data['thumb_header'] = $this->model_tool_image->resize('no_image.jpg', 200, 60);
			}
		}
		
		$data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 200, 60);
		
		if (isset($this->request->post['pdf_invoice_filename_prefix'])) {
			$data['pdf_invoice_filename_prefix'] = $this->request->post['pdf_invoice_filename_prefix'] ? true : false;
		} else {
			$data['pdf_invoice_filename_prefix'] = $this->config->get('pdf_invoice_filename_prefix');
		}
		
		if (isset($this->request->post['pdf_invoice_filename_invnum'])) {
			$data['pdf_invoice_filename_invnum'] = $this->request->post['pdf_invoice_filename_invnum'] ? true : false;
		} else {
			$data['pdf_invoice_filename_invnum'] = $this->config->get('pdf_invoice_filename_invnum');
		}
		
		if (isset($this->request->post['pdf_invoice_filename_ordnum'])) {
			$data['pdf_invoice_filename_ordnum'] = $this->request->post['pdf_invoice_filename_ordnum'] ? true : false;
		} else {
			$data['pdf_invoice_filename_ordnum'] = $this->config->get('pdf_invoice_filename_ordnum');
		}
		
		// columns
		if (isset($this->request->post['pdf_invoice_col_image'])) {
			$data['pdf_invoice_col_image'] = $this->request->post['pdf_invoice_col_image'] ? true : false;
		} else {
			$data['pdf_invoice_col_image'] = $this->config->get('pdf_invoice_col_image');
		}
		
		if (isset($this->request->post['pdf_invoice_col_product'])) {
			$data['pdf_invoice_col_product'] = $this->request->post['pdf_invoice_col_product'] ? true : false;
		} else {
			$data['pdf_invoice_col_product'] = $this->config->get('pdf_invoice_col_product');
		}
		
		if (isset($this->request->post['pdf_invoice_col_model'])) {
			$data['pdf_invoice_col_model'] = $this->request->post['pdf_invoice_col_model'] ? true : false;
		} else {
			$data['pdf_invoice_col_model'] = $this->config->get('pdf_invoice_col_model');
		}
		
		if (isset($this->request->post['pdf_invoice_col_quantity'])) {
			$data['pdf_invoice_col_quantity'] = $this->request->post['pdf_invoice_col_quantity'] ? true : false;
		} else {
			$data['pdf_invoice_col_quantity'] = $this->config->get('pdf_invoice_col_quantity');
		}
		
		if (isset($this->request->post['pdf_invoice_col_unitprice'])) {
			$data['pdf_invoice_col_unitprice'] = $this->request->post['pdf_invoice_col_unitprice'] ? true : false;
		} else {
			$data['pdf_invoice_col_unitprice'] = $this->config->get('pdf_invoice_col_unitprice');
		}
		
		if (isset($this->request->post['pdf_invoice_inlineqty'])) {
			$data['pdf_invoice_inlineqty'] = $this->request->post['pdf_invoice_inlineqty'] ? true : false;
		} else {
			$data['pdf_invoice_inlineqty'] = $this->config->get('pdf_invoice_inlineqty');
		}
		
		if (isset($this->request->post['pdf_invoice_thumbwidth'])) {
			$data['pdf_invoice_thumbwidth'] = $this->request->post['pdf_invoice_thumbwidth'];
		} else {
			$data['pdf_invoice_thumbwidth'] = $this->config->get('pdf_invoice_thumbwidth');
		}
		
		if (isset($this->request->post['pdf_invoice_thumbheight'])) {
			$data['pdf_invoice_thumbheight'] = $this->request->post['pdf_invoice_thumbheight'];
		} else {
			$data['pdf_invoice_thumbheight'] = $this->config->get('pdf_invoice_thumbheight');
		}
		
		foreach ($languages as $lang) {
			if (isset( $this->request->post[ 'pdf_invoice_filename_'.$lang['language_id'] ])) {
				$data[ 'pdf_invoice_filename_'.$lang['language_id'] ] = str_replace('/', '-', trim($this->request->post[ 'pdf_invoice_filename_'.$lang['language_id'] ]));
			} else {
				$data[ 'pdf_invoice_filename_'.$lang['language_id'] ] = $this->config->get('pdf_invoice_filename_'.$lang['language_id']);
			}
			
			if (isset( $this->request->post[ 'pdf_invoice_size_'.$lang['language_id'] ])) {
				$data[ 'pdf_invoice_size_'.$lang['language_id'] ] = trim($this->request->post[ 'pdf_invoice_size_'.$lang['language_id'] ]);
			} else {
				$data[ 'pdf_invoice_size_'.$lang['language_id'] ] = $this->config->get('pdf_invoice_size_'.$lang['language_id']);
			}
			
			if (isset( $this->request->post[ 'pdf_invoice_footer_'.$lang['language_id'] ])) {
				$data[ 'pdf_invoice_footer_'.$lang['language_id'] ] = $this->request->post[ 'pdf_invoice_footer_'.$lang['language_id'] ];
			} else {
				$data[ 'pdf_invoice_footer_'.$lang['language_id'] ] = $this->config->get('pdf_invoice_footer_'.$lang['language_id']);
			}
		}
		
		// Tab 4 - custom blocks
		$data['payment_methods'] = array();
		$payment_methods = glob(DIR_APPLICATION . 'controller/payment/*.php');
		if ($payment_methods) {
			
		// 1.5.1 language bug
			if(defined('_JEXEC')) {
				$language = $this->language;
			} else if (isset($languages[$this->config->get('config_admin_language')])) { // 1.5.1 language bug
        $lg_folder = !empty($languages[$this->config->get('config_admin_language')]['directory']) ? $languages[$this->config->get('config_admin_language')]['directory'] : $languages[$this->config->get('config_admin_language')]['code'];
        $language = new Language($lg_folder);
			} else {
        $lg_folder = !empty($languages[$this->config->get('config_language_id')]['directory']) ? $languages[$this->config->get('config_language_id')]['directory'] : $languages[$this->config->get('config_language_id')]['code'];
        $language = new Language($lg_folder);
			}
			
			
			foreach ($payment_methods as $payment) {
				$language->load('payment/' . basename($payment, '.php'));
				$data['payment_methods'][] = array(
					'code'        => basename($payment, '.php'),
					'name'       => $language->get('heading_title'),
				);
			}
		}
		
		$data['block_positions'] = array(
			'top' => $this->language->get('text_top'),
			'middle' => $this->language->get('text_middle'),
			'bottom' => $this->language->get('text_bottom'),
			'newpage' => $this->language->get('text_newpage'),
		);
		
		$data['block_displays'] = array();
		$data['block_displays'][] = array(
			'value' => 'show',
			'name' => 'Always enabled',
			'section' => 0,
		);
		
		$data['pdf_invoice_blocks'] = array();

		if (isset($this->request->post['pdf_invoice_blocks'])) {
			$data['pdf_invoice_blocks'] = $this->request->post['pdf_invoice_blocks'];
		} elseif ($this->config->get('pdf_invoice_blocks')) { 
			$data['pdf_invoice_blocks'] = $this->config->get('pdf_invoice_blocks');
		}
		
		/* gestion des variables */

		if ($this->OC_V2) {
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			$this->response->setOutput($this->load->view('module/pdf_invoice_lite.tpl', $data));
		} else {
			$data['column_left'] = '';
			$this->data = &$data;
			$this->template = 'module/pdf_invoice_lite.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	}
	
	public function tree() {
		$_POST['dir'] = urldecode($_POST['dir']);
		$root = DIR_SYSTEM . '../'.$this->config->get('pdf_invoice_backup_folder');

		if ( file_exists($root . $_POST['dir']) ) {
			$files = scandir($root . $_POST['dir']);
			natcasesort($files);
			if ( count($files) > 2 ) { /* . and .. */
				echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
				// All dirs
				foreach( $files as $file ) {
					if ( file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && is_dir($root . $_POST['dir'] . $file) ) {
						echo '<li class="directory collapsed"><a href="#" rel="' . htmlentities($_POST['dir'] . $file) . '/">' . htmlentities($file) . '</a></li>';
					}
				}
				// All files
				foreach( $files as $file ) {
					if ( file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && !is_dir($root . $_POST['dir'] . $file) && substr($file, -3) == 'pdf' ) {
						$ext = preg_replace('/^.*\./', '', $file);
						echo '<li class="file ext_'.$ext.'"><a target="new" href="'.$this->url->link('module/pdf_invoice_lite/getfile', 'dir=' . htmlentities($_POST['dir'])  . '&file=' . htmlentities($file) . '&token=' . $this->session->data['token'], 'SSL').'" rel="' . htmlentities($_POST['dir'] . $file) . '">' . htmlentities($file) . '</a></li>';
					}
				}
				echo "</ul>";	
			}
		}
		die;
	}

	public function getfile() {
		$fld = DIR_SYSTEM . '../'.$this->config->get('pdf_invoice_backup_folder') . $_GET['dir'] . '/';
		$filename =  $_GET['file'];
		
		if (!file_exists($fld.'/'.$filename))
			return 'File not found';
		
		//@ob_end_clean();
		header('Pragma: public');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Cache-Control: must-revalidate, pre-check=0, post-check=0, max-age=0');
		header("Content-Transfer-Encoding: binary");
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"".$filename."\"");
		header("Content-Length: ".(string)(filesize($fld.$filename)));
		if ($file = fopen($fld.$filename, 'rb')) {
			while (!feof($file) && (connection_status()==0)) {
				print(fread($file, 1024*8));
				flush();
			}
			fclose($file);
		}
		die;
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/pdf_invoice_lite')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	
	public function install() {
		$this->load->model('setting/setting');
		
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		
		// tables
		$this->db_tables();
		
		$ml_settings = array();
		foreach($languages as $language)
		{
			$ml_settings['pdf_invoice_filename_'.$language['language_id']] = 'Invoice';
		}
		
		$this->model_setting_setting->editSetting('pdf_invoice', array(
			'pdf_invoice_template' => 'default',
			'pdf_invoice_mail' => true,
			'pdf_invoice_tax' => $this->config->get('config_tax'),
			'pdf_invoice_invoiced' => false,
			'pdf_invoice_logo' => $this->config->get('config_logo'),
			'pdf_invoice_icon' => 'invoice-pdf1.png',
			'pdf_invoice_filename_prefix' => true,
			'pdf_invoice_filename_ordnum' => true,
			'pdf_invoice_thumbwidth' => 60,
			'pdf_invoice_thumbheight' => 60,
			'pdf_invoice_col_product' => true,
			'pdf_invoice_col_model' => true,
			'pdf_invoice_col_unitprice' => true,
			'pdf_invoice_inlineqty' => true,
			'pdf_invoice_sliptemplate' => 'default',
		) + $ml_settings);
		
		// generate bug on 1.5.1
		//$this->redirect($this->url->link('module/pdf_invoice_lite', 'token=' . $this->session->data['token'], 'SSL'));
	}

	private function db_tables() {
	
	}
}