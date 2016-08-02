<?php
define('PDF_INVOICE_ADMIN', true);
if (defined('JPATH_MIJOSHOP_OC')) {
  if(file_exists(JPATH_MIJOSHOP_OC.'/system/modification/catalog/model/tool/pdf_invoice.php')) {
    require_once(JPATH_MIJOSHOP_OC.'/system/modification/catalog/model/tool/pdf_invoice.php');
  } else {
    require_once(JPATH_MIJOSHOP_OC.'/catalog/model/tool/pdf_invoice.php');
  }
} else if (isset($vqmod)) {
	require_once($vqmod->modCheck(DIR_SYSTEM.'../catalog/model/tool/pdf_invoice.php'));
} else if (class_exists('VQMod')) {
	require_once(VQMod::modCheck(DIR_SYSTEM.'../catalog/model/tool/pdf_invoice.php'));
} else {
	require_once(DIR_SYSTEM.'../catalog/model/tool/pdf_invoice.php');
}