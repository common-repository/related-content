<?php

    function rc_get_shotcode($field, $value)
    {
    ?>
    <input type="text" value="<?php echo $value?>" onclick="RCOnFocus(this)" onchange="RCOnFocus(this)" onblur="changeValue(this, '<?php echo $value;?>');" >
    <?php
    }

    function wpadm_rp_category_show ($field, $value){


        $args = array(
        'show_option_all'    => __('All categories', 'related-content' ),
        'show_option_none'   => '',
        'orderby'            => 'ID',
        'order'              => 'ASC',
        'show_last_update'   => 0,
        'show_count'         => 0,
        'hide_empty'         => 0,
        'child_of'           => 0,
        'exclude'            => '',
        'echo'               => 1,
        'selected'           => $value,
        'hierarchical'       => 1,
        'name'               => 'wpadm-rp-category',
        'id'                 => 'name',
        'class'              => 'postform',
        'depth'              => 0,
        'tab_index'          => 0,
        'taxonomy'           => 'category',
        'hide_if_empty'      => false,
        'value_field'        => 'term_id', // значение value e option
        ); 
        wp_dropdown_categories( $args );

    }
    
    function validateType($fieldName = '') 
    {
        if (isset($_POST[$fieldName]) && !empty($_POST[$fieldName])) {
            return $_POST[$fieldName];
        } 
        return array();
    }

    function wpadm_rp_type_show ($field, $value){

        $array = array( 
        'post' => 'Posts', 
        'page' => 'Pages'
        );

        foreach($array as $name => $title ){


            if( isset( $value[$name] ) && $value[$name] == 'on' ){

                $checked = ' checked';
            } 
            else {
                $checked = '';
            } 
            

        ?>
        <div class="form_role_role_url_show-box">
            <input type="checkbox" id="<?php echo esc_attr( $field['name'] . '_' . $name) ?>" name="<?php print esc_attr( $field['name'] ) ;?>[<?php print esc_attr( $name ); ?>]" <?php print esc_attr( $checked ); ?> />
            <label for="<?php echo esc_attr( $field['name'] . '_' . $name) ?>">
                <?php print __( $title, 'wpadm-rp-' . $field['name']. '-'. $name ) ?>  
            </label> 

            <br/>
        </div>
        <?php

    }

}
