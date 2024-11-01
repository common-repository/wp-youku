<?php
defined( 'ABSPATH' ) or exit;

add_action('admin_menu', 'cwp_add_youku_meta_box');
add_action('save_post', 'cwp_save_youku_meta_box');
function cwp_add_youku_meta_box() {
    add_meta_box('wp_youku', '您要在该文章中插入的Youku视频的链接：', 'cwp_output_wp_youku_input_fields', 'post', 'normal', 'high');
    $enable_for_page=cwp_get_option('wp_youku_basics','enable_4_page',true);
    if($enable_for_page)
    add_meta_box('wp_youku', '您要在该页面中插入的Youku视频的链接：', 'cwp_output_wp_youku_input_fields', 'page', 'normal', 'high');
}
function cwp_output_wp_youku_input_fields() {
    global $post;
    echo '<input type="hidden" name="wp_youku_noncename" id="wp_youku_noncename" value="'.wp_create_nonce('wp-youku').'" />';
    echo '<input type="text" name="wp_youku" id="wp_youku"  style="width:100%;" value="'.get_post_meta($post->ID,'_wp_youku',true).'">';
}
function cwp_save_youku_meta_box($post_id) {
    if (!wp_verify_nonce($_POST['wp_youku_noncename'], 'wp-youku')) return $post_id;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
    $wp_youku = $_POST['wp_youku'];
    update_post_meta($post_id, '_wp_youku', $wp_youku);
}

/*Youku content filter*/
add_filter('the_content','wp_youku_filter_for_content');
function wp_youku_filter_for_content($content){
    global $post;
    if(!is_singular())  return $content;
    $youku_link=get_post_meta($post->ID, '_wp_youku',true) ;
    if($youku_link) $output=wp_youku_output($w, $h, $youku_link, $id=null );
    $content=$output.$content;
    return $content;
}

