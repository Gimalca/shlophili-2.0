<?php
// Heading
$_['heading_title']				= '<img src="../image/invoice/invoice-pdf1.png" style="vertical-align:top;padding-right:4px"/>PDF Invoice <span>Lite</span>';

// Text
$_['text_image_manager']		= 'Image Manager';
$_['text_browse']				= 'Browse';
$_['text_clear']				= 'Clear';
$_['text_module']				= 'Modules';
$_['text_success']         		= 'Success: You have modified module PDF Invoice Pro!';

// Entry
$_['entry_logo']				= 'Header logo :';
$_['entry_mail']				= 'Invoice with mail :<span class="help">Attach the pdf invoice in the order confirmation mail</span>';
$_['entry_admincopy']				= 'Copy for admin:<span class="help">Attach a copy of the pdf invoice in admin email<br/>Don\'t forget to enable alert mail in store settings to use this</span>';
$_['entry_mailcopy']					= 'Same email for admin:<span class="help">Send to admin the same mail than the customer\'s instead of the basic summary</span>';
$_['text_order_language']				= '- Same as order -';
$_['entry_adminlanguage']				= 'Language for admin:<span class="help">Define a specific language for pdf files generated in backoffice or sent to admin.</span>';
$_['entry_invoiced_only']		= 'Only with invoice number:<span class="help">If enabled the pdf file is available for download only once the invoice number has been generated</span>';
$_['entry_auto_generate']		= 'Auto invoice number:<span class="help">Generate the invoice number on-the-fly on order confirmation</span>';
$_['entry_manual_inv_no']			= 'Manual invoice number:<span class="help">Allow to edit or insert manually the invoice number in order edition</span>';
$_['entry_auto_notify']				= 'Attach pdf on status:<span class="help">Automatically attach pdf file to the mail sent on order status update</span>';
$_['entry_custom_fields']				= 'Display custom fields:<span class="help">Choose which custom fields to display in customer information on the invoice</span>';
$_['entry_custom_fields_empty']	= 'No custom fields defined, configure them in Sales > Customers > Custom Fields (for example if you want to add customer VAT ID)';
$_['entry_customer_id']				= 'Display customer ID:';
$_['entry_customer_format']		= 'Customer ID format:<span class="help">Set a prefix and the number of decimals you want to show<br/>Ex: CUST-00024<br />- <b>prefix</b>: CUST-<br />- <b>size</b>: 5</span>';
$_['entry_customer_prefix']		= 'Prefix:';
$_['entry_customer_size']			= 'Number size:';
$_['entry_tax']						= 'Display product prices with tax:';
$_['entry_icon']				= 'Icon :<span class="help">Choose the icon displayed in customer account > order history</span>';
$_['entry_size']							= 'Paper format:';
$_['entry_filename']				= 'Filename config:<span class="help">Choose what look like the output filename (a dash is automatically placed between values)</span>';
$_['entry_vat_number']				= 'Store VAT ID:<span class="help">Enter here your store VAT number</span>';
$_['entry_company_id']				= 'Store Company ID:<span class="help">Enter here your store company ID</span>';
$_['entry_duedate']					= 'Due date delay:<span class="help">Display due date on invoice<br />- <b>empty</b>: disabled <br />- <b>0</b>: order date<br />- <b>10</b>: 10 days from order date</span>';
$_['entry_customer_vat']			= 'Customer Company / VAT:<span class="help">Enable or disable the display of company ID field and VAT ID field for customers</span>';
$_['entry_template']				= 'Template:<span class="help">Click on the preview for full size</span>';
$_['entry_colors']					= 'Colors:<span class="help">Clear a color with the cross to reset it to default value</span>';
$_['entry_color_text']					= 'Main text';
$_['entry_color_title']					= 'Title';
$_['entry_color_thead']				= 'Table head bg';
$_['entry_color_theadtxt']			= 'Table head text';
$_['entry_color_tborder']			= 'Table borders';
$_['entry_color_footertxt']			= 'Footer text';
$_['entry_columns']					= 'Columns:<span class="help">Show or hide columns of your choice</span>';
$_['entry_col_image']					= 'Product image';
$_['entry_col_product']				= 'Product name';
$_['entry_col_model']					= 'Model';
$_['entry_col_mpn']					= 'MPN';
$_['entry_col_location']				= 'Location';
$_['entry_col_sku']					= 'SKU';
$_['entry_col_quantity']				= 'Quantity';
$_['entry_col_quantity_check']	= 'Quantity check';
$_['entry_col_expected']			= 'Expected';
$_['entry_col_unitprice']				= 'Unit price';
$_['entry_inlineqty']					= 'Quantity in product name:<span class="help">Gain space by setting the quantity in product name and disabling quantity column</span>';
$_['entry_thumbsize']					= 'Product image size:';
$_['entry_custom_comp_display']= 'Enable company ID field';
$_['entry_custom_comp_required']= 'Company ID field required';
$_['entry_custom_tax_display']	= 'Enable tax ID field';
$_['entry_custom_tax_required']	= 'Tax ID field required';
$_['entry_required']					= 'Required';
$_['entry_customer_vat_btn']		= 'Click to manage display settings for each customer groups';
$_['entry_filename_prefix_text']	= 'Filename prefix:<span class="help">Choose the prefix of the generated file to download</span>';
$_['entry_footer']						= 'Footer:<span class="help">Display some text at bottom of your invoice</span>';

$_['entry_packingslip']						= 'Packing slip:<span class="help">Send a packing slip to admin with order confirm email</span>';

$_['entry_backup']						= 'Backup pdf invoices:<span class="help">Save all invoices in a folder</span>';
$_['entry_backup_on']				= 'Backup on:<span class="help">When to generate the backup file ?</span>';
$_['entry_backup_onorder']		= 'Order confirm';
$_['entry_backup_oninvoice']		= 'Generated invoice number';
$_['entry_backup_structure']		= 'Folder structure:<span class="help">Choose how you want to structure your backup directory</span>';
$_['entry_backup_folder']			= 'Backup folder:';
$_['entry_backup_browser']		= 'Backup browser';
$_['text_backup_folder_warning']= 'Important: if you use a custom folder, make sure to copy the file .htaccess from default backup folder for security reasons.';
$_['entry_filename_prefix']		= '[prefix]';
$_['entry_filename_invnum']		= '[invoice number]';
$_['entry_filename_ordnum']		= '[order number]';

$_['entry_block_title']				= 'Title:';
$_['entry_block_message']		= 'Message:';
$_['entry_block_position']		= 'Position:';
$_['entry_block_display']		= 'Display:';
$_['entry_sort_order']			= 'Sort order:';
$_['text_top']							= 'Top';
$_['text_middle']					= 'Middle';
$_['text_bottom']					= 'Bottom';
$_['text_newpage']				= 'New page';
$_['entry_display_always']		= 'Always show';
$_['entry_display_group']		= 'User group';
$_['entry_display_orderstatus']= 'Order status';
$_['entry_display_payment']	= 'Payment type';

$_['entry_attach_pdf_invoice']	= 'Attach PDF invoice:';

$_['entry_structure_1']			= 'No subfolders';
$_['entry_structure_2']			= '/ Year';
$_['entry_structure_3']			= '/ Year / Month';
$_['entry_structure_4']			= '/ Year / Month / Day';

$_['text_manual_invoice_edit']	= 'Edit';
$_['text_manual_invoice_save']	= 'Save';

$_['text_tab_1']			= 'Main settings';
$_['text_tab_2']			= 'Template';
$_['text_tab_3']			= 'Backup';
$_['text_tab_4']			= 'Custom blocks';
$_['text_tab_5']			= 'Packing slip';
$_['text_tab_6']			= 'Invoice options';
$_['text_tab_about']	= 'About';
$_['tab_block']			= 'Block';
$_['tab_add_block']	= 'Add block';

$_['button_pdf_invoice']	= 'PDF invoice';
$_['button_packing_slip']	= 'Packing slip';

// Error
$_['error_permission']			= 'Warning: You do not have permission to modify this module!';
?>