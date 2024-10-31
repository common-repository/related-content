<?php

if ($title) {
    print '<h2>' . $title .'</h2>';
}
?>


<ul>
    <?php foreach( $posts as $post ) : ?>
     
            <li><a href="<?php print get_permalink($post->ID); ?>"><?php print $post->post_title; ?></a></li>
            
    <?php endforeach; ?>
            
    <?php wp_reset_postdata() ?>
</ul> 
 