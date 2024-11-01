<?php
/*
Plugin Name: WP Youku
Plugin URI: http://suoling.net/wp-youku
Description: 使用这个插件，你可以方便地在你的网站插入Youku视频：在编辑器下方输入某个Youku视频的链接;或者使用短代码<code>[youku w=自定义宽度 h=自定义高度]你想插入的Youku视频的链接[/youku]</code>或者<code>[youku id=你想插入的Youku视频的ID w=自定义宽度 h=自定义高度]</code>(上面短代码中的宽高是可选项,并可在插件后台设置相应的默认值),WP Youku Pro支持自动发布Youku视频为你的网站的文章,添加标题,关键词,特色图像等,并做SEO优化.
Version: 1.1
Author: suifengtec
Author URI: http://suoling.net
*/
/*
Update log:
1.0    初始发布
1.1    修改了UI中的文字错误,提高与WP Youku Pro的兼容性.
*/

defined( 'ABSPATH' ) or exit;



/*	Avoid problems with current_user_can*/
if ( !function_exists( 'wp_get_current_user' ) ) {
	if ( file_exists(ABSPATH.'wp-includes/pluggable.php') ) require_once( ABSPATH.'wp-includes/pluggable.php' );
}

add_action( 'admin_init', 'wp_youku_deactivate' );
function wp_youku_deactivate() {
    if ( current_user_can( 'activate_plugins' ) ) {
        if( is_plugin_active( 'wp-youku-pro/wp-youku-pro.php' ) ) {
            add_action( 'admin_notices', 'wp_youku_admin_notice' );
            deactivate_plugins( plugin_basename( __FILE__ ) );
            /*在Lite版本的插件中禁用掉Pro版本的插件是不合适的,所以下面我注释掉了*/
            //deactivate_plugins('wp-youku-pro/wp-youku-pro.php');

        }
    }
}


function wp_youku_admin_notice() {
   echo '<div class="error"><p><strong>WP Youku</strong> 未能启用,可能的原因是您已经启用了<strong>WP Youku Pro</strong>.</p></div>';
   /*进一步确认不可启用自身*/
   //if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
}

/*
 Get the value of a settings field
*/
 if(!function_exists('cwp_get_option')){
function cwp_get_option( $section, $option, $default = '' ) {
    $options = get_option( $section );
    if ( isset( $options[$option] ) )
        return $options[$option];
    return $default;
}
}

/*wp youku shortcode init

使用方法:
[youku w=XXX h=XXX]URL-of-a-Youku-Video[/youku]
或者
[youku id=ID-of-a-Youku-Video w=XXX h=XXX]

*/
if(!function_exists('cwp_youku_shortcode_init'))
require_once('inc/shortcode.php');

/*Settings API*/
require_once('inc/settings-api/init.php');

/*Post Meta*/
if(!function_exists('cwp_add_youku_meta_box'))
require_once('inc/post-meta.php');
/*Quicktag*/

/*add_action('admin_menu','wpYoukuregisterAdminMenu');*/
function wpYoukuregisterAdminMenu(){
        if (function_exists('add_options_page')) {
            $page_title = 'Youku视频解析';
            $menu_title = 'Youku视频解析';
            $capability = 'manage_options';
            $menu_slug = 'youku-parse';
            $function ='wp_youku_bonus';

            add_options_page($page_title, $menu_title, $capability, $menu_slug, $function);
        }
}
function wp_youku_bonus(){
	require_once('inc/youku_parse.php');
}

function wp_youku_action_links( $links, $file ) {
        $start_link  = $add_on_links = null;
        $start_link = '<a href="' . admin_url( 'options-general.php?page=wp-post-voting-pro-settings' ) . '">设置</a>';

        $add_on_links ='<a href="http://suoling.net/wp-youku" target="_blank">升级至专业版</a>';

        if ( $file == plugin_basename(__FILE__) ){
                if( !is_plugin_active( 'wp-youku-pro/wp-youku-pro.php' ) )
                    array_unshift( $links, $start_link ,$add_on_links);
        }
    return $links;
}

add_filter( 'plugin_action_links', 'wp_youku_action_links', 10, 2 );