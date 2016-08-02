<?php if(isset($_newpage)) { ?><pagebreak /><?php } ?>
<style type="text/css">
@page{
	margin: 12mm;
	footer: html_footer;
}
#footer{
	width:100%;
	font-size:11px;
	text-align: center;
	color: <?php echo $config->get('pdf_invoice_color_footertxt') ? $config->get('pdf_invoice_color_footertxt') : '#777'; ?>;
	padding-top: 5px;
	border-top: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#aaa'; ?>;
}
body{
	direction:<?php echo $direction; ?>;
	font-family: dejavusanscondensed, sans-serif;
	font-size: 12px;
	color: <?php echo $config->get('pdf_invoice_color_text') ? $config->get('pdf_invoice_color_text') : '#000'; ?>;
}
.comment p{
	margin:0
}
#title {
	position:absolute;
	<?php echo $direction == 'rtl' ? 'left':'right'; ?>:50pt;
	text-transform:uppercase;
	font-size: 24px;
	font-weight: normal;
	color: <?php echo $config->get('pdf_invoice_color_title') ? $config->get('pdf_invoice_color_title') : '#ccc'; ?>
}
#logo {
	margin-bottom: 20px;
}
.list {
	border-collapse: collapse;
	width: 100%;
	border: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#aaa'; ?>;
	margin-bottom: 20px;
}
.list td {
	padding: 7px;
	border: 1px solid <?php echo $config->get('pdf_invoice_color_tborder') ? $config->get('pdf_invoice_color_tborder') : '#ddd'; ?>;
}
.list thead td {
	background-color: <?php echo $config->get('pdf_invoice_color_thead') ? $config->get('pdf_invoice_color_thead') : '#efefef'; ?>;
	color: <?php echo $config->get('pdf_invoice_color_theadtxt') ? $config->get('pdf_invoice_color_theadtxt') : '#000'; ?>;
	font-weight: bold;
}
.list tbody td {
	vertical-align: top;
}
.rtl{text-align:right}
.rtl .right, .rtl .left{text-align:left}
.ltr .right, .rtl .left{text-align:right}
.center{text-align:center}
</style>
<h1 id="title"><?php if($invoice_no){ ?><?php echo $text_invoice; ?><?php }else{ ?><?php echo $language->get('text_proformat'); ?><?php } ?></h1>
<div class="<?php echo $direction; ?>">
<div id="logo"><a href="<?php echo $store_url; ?>"><img src="<?php echo $logo; ?>"/></a></div>
  <?php foreach($blocks_top as $block) { ?>
  <table class="comment list">
    <thead><tr><td><?php echo $block['title']; ?></td></tr></thead>
    <tbody><tr><td><?php echo $block['description']; ?></td></tr></tbody>
  </table>
  <?php } ?>
  <table class="list">
    <thead>
      <tr>
        <td colspan="2"><?php echo $text_order_detail; ?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="width:50%;">
		  <?php if($invoice_no){ ?><b><?php echo $text_invoice_no; ?></b> <?php echo $invoice_prefix . $invoice_no; ?><br /><?php } ?>
		  <b><?php echo $text_order_id; ?></b> <?php echo $order_id; ?><br />
          <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?><br />
          <?php if($customer_id){ ?><b><?php echo $text_customer_id; ?></b> <?php echo $customer_id; ?><br /><?php } ?>
          <?php if($date_due){ ?><b><?php echo $text_date_due; ?></b> <?php echo $date_due; ?><br /><?php } ?>
          <b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; ?><br />
          <?php if ($shipping_method) { ?>
          <b><?php echo $text_shipping_method; ?></b> <?php echo $shipping_method; ?>
          <?php } ?></td>
        <td>
		<?php echo $store_name; ?><br />
        <?php echo $store_address; ?><br />
		<?php if ($config->get('pdf_invoice_vat_number')) { ?><b><?php echo $language->get('text_store_vat'); ?></b> <?php echo $config->get('pdf_invoice_vat_number'); ?><br /><?php } ?>
		<?php if ($config->get('pdf_invoice_company_id')) { ?><b><?php echo $language->get('text_store_company'); ?></b> <?php echo $config->get('pdf_invoice_company_id'); ?><br /><?php } ?>
        <b><?php echo $text_telephone; ?></b> <?php echo $store_telephone; ?><br />
        <?php if ($store_fax) { ?><b><?php echo $text_fax; ?></b> <?php echo $store_fax; ?><br /><?php } ?>
        <b><?php echo $text_email; ?></b> <?php echo $store_email; ?><br />
        <b><?php echo $text_url; ?></b> <?php echo $store_url; ?>
          </td>
      </tr>
    </tbody>
  </table>
  <?php if ($comment) { ?>
  <table class="comment list">
    <thead><tr><td><?php echo $text_instruction; ?></td></tr></thead>
    <tbody><tr><td><?php echo $comment; ?></td></tr></tbody>
  </table>
  <?php } ?>
  <?php foreach($blocks_middle as $block) { ?>
  <table class="comment list">
    <thead><tr><td><?php echo $block['title']; ?></td></tr></thead>
    <tbody><tr><td><?php echo $block['description']; ?></td></tr></tbody>
  </table>
  <?php } ?>
  <table class="list">
    <thead>
      <tr>
        <td><?php echo $text_payment_address; ?></td>
        <?php if ($shipping_address) { ?>
        <td><?php echo $text_shipping_address; ?></td>
        <?php } ?>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?php echo $payment_address; ?>
			<br /><?php echo $text_email; ?> <?php echo $email; ?>
			<br /><?php echo $text_telephone; ?> <?php echo $telephone; ?>
			<?php if ($payment_company_id || $payment_tax_id) { ?><br/><?php } ?>
			<?php if ($payment_company_id) { ?><br /><?php echo $language->get('text_company_id'); ?> <?php echo $payment_company_id; ?><?php } ?>
			<?php if ($payment_tax_id) { ?><br /><?php echo $language->get('text_tax_id'); ?> <?php echo $payment_tax_id; ?><?php } ?>
      <?php foreach ($custom_fields as $custom_field) { ?>
        <br /><?php echo $custom_field['name']; ?>: <?php echo $custom_field['value']; ?>
      <?php } ?>
		</td>
        <?php if ($shipping_address) { ?>
        <td style="width:50%"><?php echo $shipping_address; ?></td>
        <?php } ?>
      </tr>
    </tbody>
  </table>
  <table class="list">
    <thead>
      <tr>
        <?php if($config->get('pdf_invoice_col_image')){ ?>		<td></td><?php } ?>
        <?php if($config->get('pdf_invoice_col_product')){ ?>	<td><?php echo $text_product; ?></td><?php } ?>
        <?php if($config->get('pdf_invoice_col_model')){ ?>		<td><?php echo $text_model; ?></td><?php } ?>
        <?php if($config->get('pdf_invoice_col_quantity')){ ?>	<td><?php echo $text_quantity; ?></td><?php } ?>
        <?php if($config->get('pdf_invoice_col_unitprice')){ ?>	<td><?php echo $text_price; ?></td><?php } ?>
        <td><?php echo $text_total; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product) { ?>
      <tr>
		<?php if($config->get('pdf_invoice_col_image')){ ?><td style="width:1px"><img src="<?php echo $product['image'] ?>" alt=""/></td><?php } ?>
		<?php if($config->get('pdf_invoice_col_product')){ ?>
        <td><?php if($config->get('pdf_invoice_inlineqty')){echo $product['quantity'].' x ';} ?><?php echo $product['name']; ?>
          <?php foreach ($product['option'] as $option) { ?>
          <br />
          &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
          <?php } ?></td>
		  <?php } ?>
        <?php if($config->get('pdf_invoice_col_model')){ ?><td><?php echo $product['model']; ?></td><?php } ?>
        <?php if($config->get('pdf_invoice_col_quantity')){ ?><td class="right" style="width:1px"><?php echo $product['quantity']; ?></td><?php } ?>
        <?php if($config->get('pdf_invoice_col_unitprice')){ ?><td class="right"><?php echo $product['price']; ?></td><?php } ?>
        <td class="right"><?php echo $product['total']; ?></td>
      </tr>
      <?php } ?>
      <?php if(isset($vouchers)) foreach ($vouchers as $voucher) { ?>
      <tr>
		<?php if($config->get('pdf_invoice_col_image')){ ?><td></td><?php } ?>
        <?php if($config->get('pdf_invoice_col_product')){ ?><td><?php echo $voucher['description']; ?></td><?php } ?>
        <?php if($config->get('pdf_invoice_col_model')){ ?><td></td><?php } ?>
        <?php if($config->get('pdf_invoice_col_quantity')){ ?><td class="right">1</td><?php } ?>
        <?php if($config->get('pdf_invoice_col_unitprice')){ ?><td class="right"><?php echo $voucher['amount']; ?></td><?php } ?>
        <td class="right"><?php echo $voucher['amount']; ?></td>
      </tr>
      <?php } ?>
      <?php foreach ($totals as $total) { ?>
      <tr>
        <td colspan="<?php echo $column_count; ?>" class="right"><b><?php echo $total['title']; ?>:</b></td>
        <td class="right"><?php echo $total['text']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  <?php foreach($blocks_bottom as $block) { ?>
  <table class="comment list">
    <thead><tr><td><?php echo $block['title']; ?></td></tr></thead>
    <tbody><tr><td><?php echo $block['description']; ?></td></tr></tbody>
  </table>
  <?php } ?>
  <?php foreach($blocks_newpage as $block) { ?>
  <pagebreak />
  <table class="comment list">
    <thead><tr><td><?php echo $block['title']; ?></td></tr></thead>
    <tbody><tr><td><?php echo $block['description']; ?></td></tr></tbody>
  </table>
  <?php } ?>
 </div>
<?php if($config->get('pdf_invoice_footer_'.$lang_id)) { ?>
<htmlpagefooter name="footer" style="display:none"><div id="footer"><?php echo $config->get('pdf_invoice_footer_'.$lang_id); ?></div></htmlpagefooter>
<?php } ?>