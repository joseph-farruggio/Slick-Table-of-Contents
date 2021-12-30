<?php

/**
    * Plugin Name: Slick Table of Contents
    * Plugin URI:        https://example.com/plugins/the-basics/
    * Description:       Create a slick table of contents
    * Version:           0.0.1
    * Requires at least: 5.2
    * Requires PHP:      7.2
    * Author:            Joey Farruggio
    * Author URI:        https://joeyfarruggio.com/
    * License:           GPL v2 or later
    * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
    * Update URI:        https://example.com/my-plugin/
    * Text Domain:       slick-table-of-contents
    * Domain Path:       /languages
 */

add_action( 'admin_init', 'child_plugin_has_parent_plugin' );
function child_plugin_has_parent_plugin() {
    if ( is_admin() && current_user_can( 'activate_plugins' ) ) {
        if (!is_plugin_active( 'advanced-custom-fields/acf.php' ) && !is_plugin_active( 'advanced-custom-fields-pro/acf.php' )) {
            add_action( 'admin_notices', 'child_plugin_notice' );

            deactivate_plugins( plugin_basename( __FILE__ ) ); 

            if ( isset( $_GET['activate'] ) ) {
                unset( $_GET['activate'] );
            }
        }
    }
}

function child_plugin_notice(){ ?>
    <div class="error"><p>Sorry, but Slick Table of Contents requires either Advanced Custom Fields or Advanced Custom Fields Pro to be installed and active.</p></div>
    <?php
}

require plugin_dir_path( __FILE__ ) . '/field-group.php';

add_action('acf/init', 'toc_init_blocks');

function toc_init_blocks() {
    // Check function exists.
    if( function_exists('acf_register_block_type') ) {
        acf_register_block_type(array(
            'name'              => 'slick-table-of-contents',
            'title'             => __('Slick Table of Contents'),
            'description'       => __('A slick table of contents.'),
            'render_template'   =>  plugin_dir_path( __FILE__ ) . '/render-template.php',
            'category'          => 'common',
            'keywords'          => array( 'table of contents', 'toc'),
            'multiple' => false,
            'enqueue_assets' => function(){
                wp_enqueue_style( 'slick-table-of-contents', plugin_dir_url( __FILE__ )  . 'assets/dist/css/slick-table-contents.css', array() );
              },
        ));
    }
}

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function toSafeID($string) {
    //Lower case everything
    $string = strtolower($string);
    //Make alphanumeric (removes all other characters)
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    //Clean up multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string;
}