<div class="vrr-wrap">
    <h1><?php echo $heading ?></h1>
    <?php
    // $options_array = get_option($this->option_name);
    // echo "<pre>";
    // var_dump($options_array);
    // echo "</pre>";
    ?>
    <?php
    settings_errors('sms_invalid_data');
    ?>

    <div id="vrr-wrap-content">
        <?php 
        $options = get_option($option_name);
        ?>
        <form id="options-save" action="options.php" method="post">
            <?php settings_fields($settings_group); ?>
            <?php echo $fields ?>
            <div class="submit-wrap">
                <?php submit_button($submit_text); ?>
                <div class="spinner"></div>
            </div>
        </form>

    </div>
    <br class="clear">
</div>
