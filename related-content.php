<?php
/*
Plugin Name: Related Content
Plugin URI: http://www.wpadm.com
Description: Related content for posts and pages
Author: WPAdm.com
Version: 1.0.1
Author URI: http://www.wpadm.com
*/

define ('WPA_RELATED_CONTENT_DIR', dirname(__FILE__) . '/');
define ('WPA_RELATED_POST_URL', plugin_dir_url(__FILE__)); 

require WPA_RELATED_CONTENT_DIR . 'class/class-related-post.php';

if (file_exists(WPA_RELATED_CONTENT_DIR . 'class/class-related-post-pro.php')) {
    require WPA_RELATED_CONTENT_DIR . 'class/class-related-post-pro.php';
    define('RELATED_CONTENT_PRO', true);
} else {
    define('RELATED_CONTENT_PRO', false);
}
 
$related_content = new related_content();

function wpadm_related_post() {
 
    global $related_content;
     
    return $related_content -> show();
}

            
add_shortcode('related-content', 'wpadm_related_post');
 



if ( is_admin() ){ 
    
     require ( WPA_RELATED_CONTENT_DIR . 'include/admin/custom_fields_functions.php' ); 
     require ( WPA_RELATED_CONTENT_DIR . 'include/admin/main-setting.php' ); 
}

