<?php
class related_content {

            
    
    var $fields = array (
        
         array(
            
            'name' => 'wpadm_rp_short_code_show',
            'title' => 'Copy this shortcode past to post or page for show related content' , 
            'default_value' => '[related-content]',
            'type' => 'custom',
            'show_function' => 'rc_get_shotcode',
            ),
        
        array(
            
            'name' => 'wpadm-rp-title',
            'title' => 'Title', 
            'type' => 'text',
            'description' => 'The Title to show for related content block',
            'save_data' => 'string',
            ),
            
        
        array(
            'name' => 'wpadm-rp-links_num',
            'title' => 'Number of links related to content', 
            'save_data' => 'int',
            'default_value' => 5), 
        
        array(
            'name' => 'wpadm-rp-random',
            'title' => 'Show related links randomly',
            'type' => 'checkbox',
            'save_data' => 'string',
            'default_value' => 'off'), 
        
        array(
            'name' => 'wpadm-rp-category',
            'title' => 'Show in Category', 
            'type' => 'custom', 
            'default_value' => 0,
            'save_data' => 'int',
            'show_function' => 'wpadm_rp_category_show',
            'description' => 'Related links will be showed in this categories',
            ), 
        
        array(
            'name' => 'wpadm-rp-show_in_child_category',
            'title' => 'Show related links in subcategories',
            'type' => 'checkbox',
            'save_data' => 'string',
            'default_value' => 'on'),
        
        array(
            'name' => 'wpadm-rp-type',
            'title' => 'Use as "related" to show links from', 
            'type' => 'custom',
            'show_function' => 'wpadm_rp_type_show',
            'save_function' => 'validateType',
            'save_data' => 'array'
            ),  
        array(
            'name' => 'rp-cache',
            'type' => 'hidden',
            'default_value' => 'gen_cache',
            ),  
        
        
    ); 
    
    var $args = array(); // args for get posts
            
    function setup_args(){
        
        $orderby = '';
        
        if( get_option('wpadm-rp-random') == 'on' ){
            
            $orderby = 'rand';
        }
        
        $links_num = get_option('wpadm-rp-links_num', 5);
        
        $category = get_option('wpadm-rp-category', false );
        
        if($category &&  get_option('wpadm-rp-show_in_child_category', 'on') == 'off'){
            
            $child_category = $this->get_child_categories($category);
             
            if($child_category){
                
                $category .= ',' . $child_category;
            }
        }
        $post_type_value = get_option('wpadm-rp-type', '' );
       
        if ($post_type_value == '' || empty($post_type_value)){
            
            $post_type = 'any';
        }
        else {
            if (is_string($post_type_value)) {
                $post_type_value = unserialize($post_type_value);
            }
			
            if( !isset($post_type_value['post']) ){
				
				$post_type_value['post'] = 'off';
				
            } elseif (empty($post_type_value['post'])) {
				
                $post_type_value['post'] = 'off';
				
            }
            if ( !isset($post_type_value['post']) ) {
				
				$post_type_value['page'] = 'off';
				
		    } elseif( empty($post_type_value['page'])){
            
                $post_type_value['page'] = 'off';
            }
            
            
            
            if ( $post_type_value['post'] == 'on' && $post_type_value['page'] == 'on'){
                
                $post_type = 'any';
            }
            
            else if ( $post_type_value['post'] == 'on' && $post_type_value['page'] == 'off' ){
                
                $post_type = 'post';
            }
            
            else if ( $post_type_value['post'] == 'off' && $post_type_value['page'] == 'on' ){
                
                $post_type = 'page'; 
            }
        }
         
        
        
    
        
        $this->args = array( 
            'posts_per_page' => $links_num, 
            'orderby' => $orderby,
            'category' => $category,
            'post_type' => $post_type,
            'post_status' => 'publish',
            'exclude' => get_the_ID() );

    }        
    
    function get_child_categories($category_id){
        
        $args = array(
               'type'                     => 'post',
               'child_of'                 => $category_id,
               'parent'                   => '', 
               'hide_empty'               => 0,
               'hierarchical'             => 0,
               'exclude'                  => '',
               'include'                  => '',
               'number'                   => 0,
               'taxonomy'                 => 'category',
               'pad_counts'               => false
       );
        
       $categories = get_categories( $args );

       $result = array();

       foreach ($categories as $value) {

           $result[] = '-' . $value->term_id;

       }

       $result = implode(',', $result);
       
       return $result;   
        
    }
    
    function show(){
        
        ob_start();
         
        
        $this -> setup_args();
        
        $title = get_option('wpadm-rp-title', '');
         
        
        $posts = get_posts( $this -> args );
         
        if( ! count($posts) ){
            
            return false;
        }
        
        require_once WPA_RELATED_CONTENT_DIR . 'include/template.php';
            
        
        return ob_get_clean();     
    }
       
}
