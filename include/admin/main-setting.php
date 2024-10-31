<?php


    // Hook for adding admin menus
    add_action('admin_menu', 'wpadm_rp_add_pages');

    // action function for above hook
    function wpadm_rp_add_pages() {

        // Add a new submenu under Options:
        add_options_page('Wpadm-login-title', 'Related Content', 'activate_plugins', 'related-content-main-setting', 'wpadm_rp_main_setting');

    } 
    function RC_admin_scripts() {

        if ( isset($_GET['page']) && $_GET['page'] == 'related-content-main-setting'){

            wp_enqueue_script('jquery');
            wp_enqueue_script('media-upload');
            //wp_register_script('related-content-js',  WPA_RELATED_CONTENT_DIR . 'include/js/scripts.js', array('jquery','media-upload'));
            wp_enqueue_script('related-content-js', plugins_url( "/js/scripts.js", dirname( __FILE__  ) ) );
        }
    }

    function RC_admin_styles(){

        if (isset($_GET['page']) && $_GET['page'] == 'related-content-main-setting') {
            wp_enqueue_style('related-content-css', plugins_url( "/css/admin-styles.css", dirname( __FILE__  ) )); 
        }
    }



    add_action('admin_print_scripts', 'RC_admin_scripts');
    add_action('admin_print_styles', 'RC_admin_styles');



    function wpadm_rp_main_setting() {


        global $related_content; 

        $fields = $related_content -> fields; 

        $hidden_field_name = 'mt_submit_hidden'; 


        if( !empty($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) { 

            foreach($fields as $field) {

                if( ! empty($field['type']) && $field['type'] == 'title'){

                    continue;
                }

                if( empty( $field['save_function'] )) { 


                    if ( isset($field['type']) && $field['type'] == 'checkbox' && ! isset($_POST[$field['name']])){

                        $_POST[$field['name']] = 'off';
                    }

                    if( ! isset($_POST[$field['name']] )){

                        continue; 
                    }

                    $value_for_update =  isset( $_POST[$field['name']] ) && !empty( $_POST[$field['name']] ) ? $_POST[$field['name']] : '';
                } else {

                    $value_for_update = $field['save_function']($field['name']);

                }

                if (isset($field['save_data'])) {
                    switch($field['save_data']) {
                        case 'string' :
                            if (is_string($value_for_update)) {
                                $value_for_update = stripslashes( strip_tags( trim( $value_for_update ) ) );
                            } elseif (isset($field['default_value'])) {
                                $value_for_update = $field['default_value']; 
                            }
                            $value_for_update = sanitize_text_field($value_for_update);
                            break;
                        case 'int':
                            $value_for_update = (int)$value_for_update;
                            if (is_int($value_for_update)) {
                                $value_for_update = $value_for_update;
                            } elseif (isset($field['default_value'])) {
                                $value_for_update = $field['default_value'];
                            }
                            $value_for_update = sanitize_text_field($value_for_update);
                            break;
                        case 'array' : 
                            if(is_array($value_for_update)) {
                                $values = array();
                                foreach($value_for_update as $key => $value_) {
                                    $key_ = sanitize_text_field( stripslashes( strip_tags( trim( $key ) ) ) );
                                    $val_ = sanitize_text_field( stripslashes( strip_tags( trim( $value_ ) ) ) );
                                    $values[$key_] = $val_;
                                }
                                $value_for_update =  $values;
                            }
                            break;
                        default :
                            break;
                    }
                }
                
                update_option( sanitize_key($field['name']), $value_for_update );
            }


        ?>
        <div class="updated"><p><strong><?php _e('Setting was saved', 'wpadm-rp-setting-saving-success' ); ?></strong></p></div>
        <?php

        }

        // Now display the options editing screen

        echo '<div class="wrap">';

        // header

        echo "<h2>" . __( 'Related Content settings', 'related-content' ) . "</h2>";

        // options form

    ?> 
    <form name="form1" method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
        <div>

            <table class="form-table">
                <?php

                    foreach($fields as $field): 


                        $attr = '';

                    ?>

                    <tr valign="top">
                        <th scope="row">
                            <?php

                                if ( !empty( $field['type']) && $field['type'] == 'title' ) {

                                    print '<h2>' . esc_html( __( $field['title'], 'related-content' ) ).'</h2>';
                                }
                                else {
                                    if (isset($field['title'])) {
                                        print esc_html( __( $field['title'], 'related-content' ) ) ;
                                    }

                                } 

                            ?>
                        </th>
                        <td valign="top"> 

                            <?php

                                if ( ! empty($field['name'])){

                                    if( isset($field['default_value'])) {

                                        $value = get_option( $field['name'], $field['default_value'] ) ; 
                                    }
                                    else {

                                        $value = get_option( $field['name'] ); 
                                    }

                                } 


                                if ( !empty( $field['type']) and $field['type'] == 'text-custom' ) {

                                    echo esc_html( __( $field['text'], 'related-content' ) );

                                } elseif ( empty( $field['type']) or $field['type'] == 'text' ) {
                                ?>
                                <input type="text" name="<?php echo esc_attr( $field['name'] ); ?>" value="<?php echo esc_attr( $value ); ?>" <?php print esc_attr( $attr )?> />
                                <?php
                                } elseif ( $field['type'] == 'checkbox' ) {

                                    if($value == 'on'){

                                        $checked = ' checked';
                                    }
                                    else {
                                        $checked = '';
                                    }

                                ?> 
                                <input type="checkbox" name="<?php echo esc_attr( $field['name'] ); ?>" <?php print esc_attr( $checked ); ?> <?php print esc_attr( $attr ); ?> />
                                <?php

                                } elseif($field['type'] == 'file'){

                                ?>
                                <input type="text"  name="<?php echo esc_attr( $field['name'] ) ;?>" value="<?php echo esc_attr( $value ) ?>" />
                                <input type="button" class="onetarek-upload-button button" value="<?php _e( 'Upload file', 'Upload-file')?>" />
                                <input type="button" class="prev_reset button" value="<?php _e( 'Reset', 'reset-file')?>" /> 
                                <?php
                                } elseif ($field['type'] == 'select') {

                                ?>
                                <select name="<?php echo $field['name']?>">

                                    <?php

                                        if ( ! empty($field['values']) ) {

                                            foreach($field['values'] as $f_key => $f_value ){

                                                print '<option value="' . $f_key . '"';

                                                if($f_key == $value){

                                                    print ' selected';
                                                }

                                                print '>' . __( $f_value, 'related-content' ) . '</option>';
                                            }
                                        }


                                    ?>


                                </select>
                                <?php
                                } elseif ($field['type'] == 'radio') {


                                    if ( ! empty($field['values']) ) {

                                        foreach($field['values'] as $f_key => $f_value ){



                                            print '<input type="radio" id="' . esc_attr( $field['name'] ) . '" name="' . esc_attr( $field['name'] ) . '" value="' . esc_attr( $f_key ) . '" ';
                                            if($f_key == $value){

                                                print ' checked';
                                            }

                                            print ' />';

                                            print '<label for="' . esc_attr($field['name']) . '">' . esc_html( __( $f_value, 'related-content' ) ) . '</label>';

                                            print '<br>';

                                        }
                                    } 
                                } elseif($field['type'] == 'custom'){


                                    if( $field['show_function'] && function_exists($field['show_function'])){

                                        $field['show_function']($field, $value); 
                                    }


                                }  

                                if ( ! empty( $field['description']) ) {

                                    print '<p class="description">' . esc_attr( __( $field['description'], 'related-content' ) ) . '</p>';
                                }

                            ?>
                        </td>
                    </tr>

                    <?php 

                        endforeach;

                ?> 

            </table>



            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

            <hr />

            <p class="submit">
                <input type="submit" name="Submit" class="button button-primary button-large" value="<?php _e('Save', 'related-content' ) ?>" />
            </p>
        </div>




    </form>
    </div>



    <?php




}

 
 