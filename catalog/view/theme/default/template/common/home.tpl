<?php echo $header; ?>

<div class="main-container">
    <div class="container imghome">
        <div class="row"><?php echo $column_left; ?>
            <?php if ($column_left && $column_right) { ?>
            <?php $class = 'col-sm-6'; ?>
            <?php } elseif ($column_left || $column_right) { ?>
            <?php $class = 'col-sm-9'; ?>
            <?php } else { ?>
            <?php $class = 'col-sm-12'; ?>
            <?php } ?>
            <div id="content" class="<?php echo $class; ?>">
                <?php echo $content_top; ?><?php echo $content_bottom; ?>
                <div style="margin-top: 100px;" class="row">
                    <center>
                        <a href="index.php?route=account/login">
                            <button type="button" class="btn btn-default">Si es Agente Autorizado haga click aquí</button>
                        </a>
                    </center>
                </div>
            </div>
            <?php echo $column_right; ?>
            
        </div>
        
    </div>
</div>

<?php echo $footer; ?>