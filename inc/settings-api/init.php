<?php
defined( 'ABSPATH' ) or exit;
/*require_once 'class.settings-api.php';*/
require_once dirname( __FILE__ ) . '/class.settings-api.php';
/**
 * WordPress settings API demo class
 */
if ( !class_exists('WP_YOUKU_SETTINGS' ) ):
class WP_YOUKU_SETTINGS {

    private $settings_api;

    function __construct() {
        $this->settings_api = new WeDevs_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_options_page( 'WP-Youku设置', 'WP-Youku设置', 'manage_options', 'wp-youku', array($this, 'plugin_page') );
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'wp_youku_basics',
                'title' => '综合设置'
            ),
          /*  array(
                'id' => 'wp_post_voting_pro_advanced',
                'title' => __( 'Advanced Settings', 'cwp' )
            ),
            array(
                'id' => 'wp_post_voting_pro_others',
                'title' => __( 'Other Settings', 'cwp' )
            )*/
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
            'wp_youku_basics' => array(
                array(
                    'name' => 'enable_4_page',
                    'label' => '是否在页面上插入Youku视频?',
                    'desc' => '默认是可以在页面上用WP Youku插入Youku视频的,您可以选择禁用。',
                    'type' => 'select',
                    'default' => 'yes',
                    'options' => array(
                        'yes' => '是',
                         'no' => '否'
                        )
                ),
                array(
                    'name' => 'youku_w',
                    'label' => 'Youku视频默认的宽度',
                    'desc' => '以像素为单位设置站内Youku视频默认的宽度',
                    'type' => 'text',
                    'default' => '600',
                    'sanitize_callback' => 'intval'
                ),
                array(
                    'name' => 'youku_h',
                    'label' => 'Youku视频默认的高度',
                    'desc' => '以像素为单位设置站内Youku视频默认的高度',
                    'type' => 'text',
                    'default' => '480',
                    'sanitize_callback' => 'intval'
                ),

            ),

        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
endif;

$settings = new WP_YOUKU_SETTINGS();