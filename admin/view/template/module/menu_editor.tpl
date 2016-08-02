<?php

echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-menu_editor" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-menu_editor" class="form-horizontal">
                    <!-- STATUS -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                        <div class="col-sm-10">
                            <select name="menu_editor_enabled" id="input-status" class="form-control">
                                <?php if ($menu_editor_enabled) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select>

                        </div>
                    </div>
                    <?php $row_count=0; foreach ($menu_editor_entries as  $menu_editor_entry) { ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-position-<?php echo $row_count; ?>"><?php echo $entry_position; ?></label>
                        <div class="col-sm-2">
                            <select name="menu_editor_entries[<?php echo $row_count; ?>][position]" id="input-position-<?php echo $row_count; ?>" class="form-control">
                                <option value="0" <?php if ($menu_editor_entry['position']==0){echo " selected";} ?>><?php echo $text_left; ?></option>
                                <option value="1" <?php if ($menu_editor_entry['position']==1){echo " selected";} ?>><?php echo $text_right; ?></option>
                            </select>
                        </div>
                        <label class="col-sm-1 control-label" for="input-href-<?php echo $row_count; ?>"><?php echo $entry_href; ?></label>
                        <div class="col-sm-4">
                            <input name="menu_editor_entries[<?php echo $row_count; ?>][href]" id="input-href-<?php echo $row_count; ?>" class="form-control" value="<?php echo $menu_editor_entry['href']; ?>">
                        </div>


                        <label class="col-sm-1 control-label" for="input-preset-<?php echo $row_count; ?>"><?php echo $entry_preset; ?></label>
                        <div class="col-sm-2">
                            <select name="preset" id="input-preset-<?php echo $row_count; ?>" class="form-control" onchange="syncPreset(this)" preset-for="<?php echo $row_count; ?>">
                                <option value=""><?php echo $text_please_select; ?></option>
                                <?php foreach ($presets as $preset) { ?>
                                <option value="<?php echo $preset['value']; ?>" 
                                        <?php foreach ($preset['names'] as $language_id => $name) { echo " data-name$language_id='$name' ";} ?>       
                                        ><?php echo $preset['text']; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <label class="col-sm-2 control-label" style="margin-top: 10px;" for="name-<?php echo $row_count; ?>"><?php echo $entry_name; ?></label>
                        <div class="col-sm-7" id="name-<?php echo $row_count; ?>" style="margin-top: 10px;">
                            <?php foreach ($languages as $language) { ?>
                            <div class="input-group pull-left" style="margin-bottom: 5px;" id="name-<?php echo $row_count; ?>">
                                <span class="input-group-addon">
                                    <img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" 
                                         title="<?php echo $language['name']; ?>" /> </span>
                                <input type="text" style="resize: vertical;" 
                                       id="menu_editor_entries_<?php echo $row_count; ?>_names_<?php echo $language['language_id']; ?>"
                                       name="menu_editor_entries[<?php echo $row_count; ?>][names][<?php echo $language['language_id']; ?>]" class="form-control name-<?php echo $language['language_id']; ?>" 
                                       value="<?php if (isset($menu_editor_entry['names'][$language['language_id']])){echo $menu_editor_entry['names'][$language['language_id']];} ?>">
                            </div>


                            <?php } ?>
                                        <?php if (isset($error_entries[$row_count])) { ?>
                            <div class="text-danger"><?php echo $error_empty_line; ?></div>

        <?php } ?>
                        </div>
                        
                            <label class="col-sm-1 control-label" style="margin-top: 10px;" for="input-target-<?php echo $row_count; ?>"><?php echo $entry_target; ?></label>
                            <div class="col-sm-2" style="margin-top: 10px;">
                                <select name="menu_editor_entries[<?php echo $row_count; ?>][target]" id="input-target-<?php echo $row_count; ?>" class="form-control" target-for="<?php echo $row_count; ?>">
                                    <option value="" <?php if ($menu_editor_entry['target']==""){echo " selected";} ?>><?php echo $text_please_select; ?></option>
                                    <option value="_self" <?php if ($menu_editor_entry['target']=="_self"){echo " selected";} ?>><?php echo $text_target_self; ?></option>
                                    <option value="_blank" <?php if ($menu_editor_entry['target']=="_blank"){echo " selected";} ?>><?php echo $text_target_blank; ?></option>
                                    <option value="_parent" <?php if ($menu_editor_entry['target']=="_parent"){echo " selected";} ?>><?php echo $text_target_parent; ?></option>
                                    <option value="_top" <?php if ($menu_editor_entry['target']=="_top"){echo " selected";} ?>><?php echo $text_target_top; ?></option>
                                
                                </select>
                            </div>
                        
                        <div class="col-sm-1 col-sm-offset-11 text-right"><button style="margin-top: 10px;" type="button"
                                                      onclick="removeRow(this);return false;" 
                                                      class="btn btn-danger pull-right"><i class="fa fa-trash"></i></button></div>
                    </div>
                    <?php $row_count++; } ?>





                </form>
                <div class="col-sm-12" style="padding-right:0px;"><button onclick="addRow()" class="btn btn-primary pull-right"><i class="fa fa-plus"></i></button></div>
            </div>
        </div>
    </div>
    <script type="text/javascript">

        function syncPreset(that) {
            var href = $(that).val();
            var preset = $(that).attr('preset-for');
            var input_href = "input-href-" + preset;
        <?php foreach ($languages as $language) { ?>
            $("#menu_editor_entries_" + preset + "_names_<?php echo $language['language_id']; ?>").val($("#input-preset-" + preset + "  option:selected").attr("data-name<?php echo $language['language_id']; ?>"));

        <?php } ?>
            $("#" + input_href).val(href);
        }

        row_count = <?php echo $row_count; ?>;

        function addRow() {
            var html = "";
            html += '<div class="form-group">';
            html += '<label class="col-sm-2 control-label" for="input-position-' + row_count + '"><?php echo $entry_position; ?></label>';
            html += '<div class="col-sm-2">';
            html += '<select name="menu_editor_entries[' + row_count + '][position]" id="input-position-' + row_count + '" class="form-control input-position">';
            html += '<option value="0" select><?php echo $text_left; ?></option>';
            html += '<option value="1"><?php echo $text_right; ?></option>';
            html += '</select>';
            html += '</div>';
            html += '<label class="col-sm-1 control-label" for="input-href-' + row_count + '"><?php echo $entry_href; ?></label>';
            html += '<div class="col-sm-4">';
            html += '<input name="menu_editor_entries[' + row_count + '][href]" id="input-href-' + row_count + '" class="form-control input-href" value="">';
            html += '</div>';

            html += '<label class="col-sm-1 control-label" for="input-preset-' + row_count + '"><?php echo $entry_preset; ?></label>';
            html += '<div class="col-sm-2">';
            html += '<select name="preset" id="input-preset-' + row_count + '" class="form-control input-preset" onchange="syncPreset(this)"  preset-for="' + row_count + '">';
            html += '<option value=""><?php echo $text_please_select; ?></option>';
                <?php foreach ($presets as $preset) { ?>
            html += '<option ';
                    <?php foreach ($preset['names'] as $language_id => $name) { ?>
            html += ' data-name<?php echo $language_id; ?>="<?php echo $name; ?>" ';
                    <?php } ?>
            html += 'value="<?php echo $preset["value"]; ?>"><?php echo $preset['text']; ?></option>';
                <?php } ?>
            html += '</select>';
            html += '</div>';

            html += '<label class="col-sm-2 control-label" style="margin-top: 10px;" for="name-' + row_count + '"><?php echo $entry_name; ?></label>';
            html += '<div class="col-sm-7" id="name-' + row_count + '" style="margin-top: 10px;">';
                <?php foreach ($languages as $language) { ?>
            html += '<div class="input-group pull-left" style="margin-bottom: 5px;" id="name-' + row_count + '">';
            html += '<span class="input-group-addon">';
            html += '<img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> </span>';
            html += '<input id="menu_editor_entries_' + row_count + '_names_<?php echo $language['language_id']; ?>" type="text" style="resize: vertical;" name="menu_editor_entries[' + row_count + '][names][<?php echo $language['language_id']; ?>]" class="form-control" value="">';

            html += '</div>';

                <?php } ?>

            html += '</div>';
            html += '<label class="col-sm-1 control-label" style="margin-top: 10px;" for="input-target-' + row_count + '"><?php echo $entry_target; ?></label>';
            html += '<div class="col-sm-2" style="margin-top: 10px;">';
            html += '<select name="menu_editor_entries[' + row_count + '][target]" id="input-target-' + row_count + '" class="form-control" target-for="' + row_count + '">';
            html += '<option value=""><?php echo $text_please_select; ?></option>';
            html += '<option value="_self"><?php echo $text_target_self; ?></option>';
            html += '<option value="_blank"><?php echo $text_target_blank; ?></option>';
            html += '<option value="_parent"><?php echo $text_target_parent; ?></option>';
            html += '<option value="_top"><?php echo $text_target_top; ?></option>';
            html += '</select>';
            html += '</div>';
            html += '<div class="col-sm-1 col-sm-offset-11 text-right"><button  type="button" style="margin-top: 10px;" onclick="removeRow(this)" class="btn btn-danger pull-right"><i class="fa fa-trash"></i></button></div>';
            html += '</div>';

            $("#form-menu_editor").append(html);
            row_count++;

        }

        function removeRow(that) {
            $(that).closest(".form-group").remove();
        }


    </script>
</div>
    <?php echo $footer; ?>