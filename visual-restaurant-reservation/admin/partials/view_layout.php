<div class="vrr-wrap">
    <h1 class="vrr-h1"><?php echo $heading ?></h1>
    <div id="vrr-wrap-content">

        <form class="vrr-form vrr-form-options-save" id="vrr-options-save" action="options.php" method="post">

            <div class="vrr-top-setting-wrap">
                <!-- Backgrounds -->
                <div class="vrr-background-field-wrap-all">
                    <div class="vrr-background-field-wrap" data-background="background-canvas">
                        <h3 class="vrr-h3">
                            <label><?php _e('Background Canvas','vrr');?></label>
                        </h3>
                        <?php echo $background_canvas; ?>
                    </div>
                    <div class="vrr-background-field-wrap" data-background="background-table">
                        <h3 class="vrr-h3">
                            <label><?php _e('Background Table','vrr');?></label>
                        </h3>
                        <?php echo $background_table; ?>
                    </div>
                    <div class="vrr-background-field-wrap" data-background="background-seat">
                        <h3 class="vrr-h3">
                            <label><?php _e('Background Seat','vrr');?></label>
                        </h3>
                        <?php echo $background_seat; ?>
                    </div>
                </div>
            </div>
            
            <?php settings_fields($settings_group); ?>
            <?php echo $fields ?>

            <div class="vrr-layout-wrap">
                <?php
                $options = get_option($option_name);
                
                if(isset($options['position']) && !empty($options['position'])){
                    $postion = urldecode($options['position']);
                    $jsonIterator = json_decode($postion, TRUE);
                }
                ?>
                <div class="vrr-tables-wrap vrr-loading vrr-tables-size-<?php echo $settings['canvas_elements_size']; ?>">

                    <div class="vrr-element-sidebar">
                        <div class="vrr-element-sidebar-title"><?php _e('Tables', 'vrr');?></div>
                        <div class="vrr-element-sidebar-inner">
                            <div class="vrr-element-sidebar-wrap-all">
                            <?php
                            $iter = 0;
                            $all_tables = count($tables);
                            ?>
                            <?php foreach ($tables as $key => $table) { ?>

                                <?php $iter++; ?>
                                <?php if($iter == 1){ ?>
                                    <div class="vrr-element-sidebar-row">
                                <?php } ?>

                                <div class="vrr-element-sidebar-wrap">
                                    <div class="vrr-element-sidebar-wrap-inner">

                                        <div class="vrr-element <?php echo $table['data-type']; ?> rotate-<?php echo $table['data-rotate']; ?>" data-id="" data-unique="" data-max-seats="<?php echo $table['data-max-seats']; ?>" data-seats="<?php echo $table['data-seats']; ?>" data-big-side="<?php echo $table['data-big-side']; ?>" data-type="<?php echo $table['data-type']; ?>" data-rotate="<?php echo $table['data-rotate']; ?>">
                                            <div class="vrr-element-table">
                                                <div class="vrr-element-seats"><?php echo $table['data-seats']; ?></div>

                                                <div class="vrr-element-seats-wrap">
                                                    <?php
                                                    $seats = intval($table['data-seats']);
                                                    for ($i=0; $i < $seats; $i++) { ?>
                                                        <div class="vrr-element-seat"></div>
                                                    <?php } ?>
                                                </div>

                                                <div class="vrr-element-edit-wrap" style="display: none;">
                                                    <div class="vrr-element-edit-action">
                                                        <div class="vrr-element-edit-seats-icon"></div>
                                                        <div class="vrr-element-edit-seats vrr-element-edit-action">
                                                            <div class="vrr-element-edit-seats-minus">-</div>
                                                            <div class="vrr-element-edit-seats-input vrr-element-edit-input"><?php echo $table['data-seats']; ?></div>
                                                            <div class="vrr-element-edit-seats-plus">+</div>
                                                        </div>
                                                    </div>

                                                    <div class="vrr-element-edit-action">
                                                        <div class="vrr-element-edit-rotate vrr-element-edit-btn"></div>
                                                    </div>

                                                    <div class="vrr-element-edit-id vrr-element-edit-action">
                                                        <span class="vrr-element-edit-id-icon"></span>
                                                        <input type="text" step="1" min="1" max="99" value="" class="vrr-element-edit-id-input vrr-input">
                                                    </div>

                                                    <div class="vrr-element-edit-action">
                                                        <div class="vrr-element-delete vrr-element-edit-btn"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <?php if($iter == 1 || $key+1 == $all_tables){ ?>
                                    </div>
                                    <?php $iter = 0; ?>
                                <?php } ?>

                            <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="vrr-draggable-wrap-all">
                        <div class="vrr-element-canvas-title-wrap">
                            <div class="vrr-element-canvas-title"><?php _e('Layout', 'vrr');?></div>
                            <div class="vrr-canvas-size-settings">

                                <div id="settings-canvas_elements_size" class="settings-canvas_elements_size vrr-canvas-elements-size-settings">
                                    <h3 class="vrr-h3 vrr-sizes-title">
                                        <label for="visual_restaurant_reservation_settings[canvas_elements_size]"><?php _e('Tables Size','vrr'); ?></label>
                                    </h3>
                                    <div>
                                        <select class="vrr-input" id="visual_restaurant_reservation_settings[canvas_elements_size]" name="visual_restaurant_reservation_settings[canvas_elements_size]">
                                            <option <?php if($settings['canvas_elements_size'] == "medium"){ echo "selected";} ?> value="medium">Medium</option>
                                            <option <?php if($settings['canvas_elements_size'] == "small"){ echo "selected";} ?> value="small">Small</option>
                                            <option <?php if($settings['canvas_elements_size'] == "big"){ echo "selected";} ?> value="big">Big</option>
                                        </select>
                                    </div>

                                    <div class="settings-canvas-description"><?php _e('Default: Medium','vrr'); ?></div>
                                </div>

                                <div id="settings-canvas_width" class="settings-canvas_width vrr-canvas-width-settings">
                                    <h3 class="vrr-h3 vrr-sizes-title">
                                        <label for="visual_restaurant_reservation_settings[canvas_width]"><?php _e('Canvas Width','vrr'); ?></label>
                                    </h3>
                                    <div>
                                        <input class="vrr-input" placeholder="<?php _e('min','vrr'); ?>: 500 | <?php _e('max','vrr'); ?>: 1500" id="visual_restaurant_reservation_settings[canvas_width]" name="visual_restaurant_reservation_settings[canvas_width]" type="number" min="1" value="<?php echo $settings['canvas_width']; ?>"><span class="vrr-h3 vrr-px">px</span>
                                    </div>

                                    <div class="settings-canvas-description"><?php _e('min','vrr'); ?>: 500 | <?php _e('max','vrr'); ?>: 1500</div>
                                </div>

                                <div id="settings-canvas_height" class="settings-canvas_height vrr-canvas-height-settings">
                                    <h3 class="vrr-h3 vrr-sizes-title">
                                        <label for="visual_restaurant_reservation_settings[canvas_height]"><?php _e('Canvas Height','vrr'); ?></label>
                                    </h3>
                                    <div>
                                        <input class="vrr-input" placeholder="<?php _e('min','vrr'); ?>: 500 | <?php _e('max','vrr'); ?>: 1500" id="visual_restaurant_reservation_settings[canvas_height]" name="visual_restaurant_reservation_settings[canvas_height]" type="number" min="1" value="<?php echo $settings['canvas_height']; ?>"><span class="vrr-h3 vrr-px">px</span>
                                    </div>

                                    <div class="settings-canvas-description"><?php _e('min','vrr'); ?>: 500 | <?php _e('max','vrr'); ?>: 1500</div>
                                </div>
                            </div>
                        </div>
                        <div class="vrr-draggable-wrap">
                            <div class="vrr-draggable " style="<?php if($options['canvas_width']){ ?>width:<?php echo $options['canvas_width'];} ?>px;<?php if($options['canvas_height']){ ?>height:<?php echo $options['canvas_height'];} ?>px;">
                                <?php
                                if(isset($jsonIterator) && !empty($jsonIterator)){
                                    foreach ($jsonIterator as $key => $val) {
                                        if(is_array($val)) {
                                            $w = $val['x1']-$val['x'];
                                            $h = $val['y1']-$val['y'];
                                            $element_style = "left: ".$val['x']."px; top: ".$val['y']."px;";
                                            ?>
                                            <div
                                            class="vrr-element <?php if($val['class']){echo $val['class'];} ?> <?php if($val['rotate']){echo 'rotate-'.$val['rotate'];} ?>"
                                            data-id="<?php echo $val['id']; ?>"
                                            data-unique="<?php echo $val['unique']; ?>"
                                            data-seats="<?php echo $val['seats']; ?>"
                                            data-max-seats="<?php echo $val['max_seats']; ?>"
                                            data-big-side="<?php echo $val['big_side']; ?>"
                                            data-type="<?php echo $val['class']; ?>"
                                            data-rotate="<?php echo $val['rotate']; ?>"
                                            style="<?php echo $element_style; ?>">
                                                <div class="vrr-element-table">
                                                    <div class="vrr-element-edit"></div>
                                                    <div class="vrr-element-id" data-default="<?php echo $val['id']; ?>"><?php echo $val['id']; ?></div>
                                                    <div class="vrr-element-seats"><?php echo $val['seats']; ?></div>

                                                    <div class="vrr-element-seats-wrap">
                                                        <?php
                                                        $seats = intval($val['seats']);
                                                        for ($i=0; $i < $seats; $i++) { ?>
                                                            <div class="vrr-element-seat"></div>
                                                        <?php } ?>
                                                    </div>

                                                    <div class="vrr-element-edit-wrap" style="display: none;">
                                                        <div class="vrr-element-edit-action">
                                                            <div class="vrr-element-edit-seats-icon"></div>
                                                            <div class="vrr-element-edit-seats ">
                                                                <div class="vrr-element-edit-seats-minus">-</div>
                                                                <div class="vrr-element-edit-seats-input vrr-element-edit-input"><?php echo $val['seats']; ?></div>
                                                                <div class="vrr-element-edit-seats-plus">+</div>
                                                            </div>
                                                        </div>

                                                        <div class="vrr-element-edit-action">
                                                            <div class="vrr-element-edit-rotate vrr-element-edit-btn"></div>
                                                        </div>

                                                        <div class="vrr-element-edit-id vrr-element-edit-action">
                                                            <span class="vrr-element-edit-id-icon"></span>
                                                            <input type="text" step="1" min="1" max="99" value="<?php echo $val['id']; ?>" class="vrr-element-edit-id-input vrr-input">
                                                        </div>

                                                        <div class="vrr-element-edit-action">
                                                            <div class="vrr-element-delete vrr-element-edit-btn"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <?php
            $canvas_style = '';
            $element_style = '';
            $seat_style = '';
            if(isset($options['background-canvas']) && !empty($options['background-canvas'])){
                $canvas_style = "background-image: url(". $options['background-canvas'].");";
            }
            if(isset($options['background-table']) && !empty($options['background-table'])){
                $element_style = "background-image: url(". $options['background-table'].");";
            }
            if(isset($options['background-seat']) && !empty($options['background-seat'])){
                $seat_style = "background-image: url(". $options['background-seat'].");";
            }
            ?>
            <style type="text/css">
                .vrr-draggable:not(.no-bg){
                    <?php echo $canvas_style; ?>
                }
                .vrr-element:not(.no-bg-table) .vrr-element-table{
                    <?php echo $element_style; ?>
                }
                .vrr-element:not(.no-bg-seats) .vrr-element-seats-wrap .vrr-element-seat{
                    <?php echo $seat_style; ?>
                }
            </style>

            <div class="vrr-instructions-text">
                <span class="vrr-instructions-shortcode-wrap" >
                    <h3 class="vrr-h3"><?php _e('Shortcode:','vrr'); ?></h3>
                    <div class="">
                        <span id="vrr_instructions_shortcode"  data-clipboard-action="copy" class="vrr-instructions-shortcode vrr-h3">[visual_restaurant_reservation]</span>
                    </div>
                    <div class="vrr-instructions-shortcode-btn" data-clipboard-target="#vrr_instructions_shortcode"></div>
                </span>
                <span><h3 class="vrr-h3"><?php _e('Use this shortcode to output layout on your page.','vrr'); ?></h3></span>
            </div>

            <div class="vrr-submit-wrap">
                <?php submit_button($submit_text); ?>
                <div class="vrr-spinner"></div>
            </div>

        </form>

    </div>
    <br class="clear">
</div>
