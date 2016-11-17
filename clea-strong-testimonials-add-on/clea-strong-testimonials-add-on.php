<?php
/**
* Plugin Name: Clea Strong Testimonials Add-on
* Plugin URI:  http://knowledge.parcours-performance.com
* Description: Add a new taxonomy to Strong Testimonials
* Author:      Anne-Laure Delpech
* Author URI:  http://knowledge.parcours-performance.com
* License:     GPL2
* Domain Path: /languages
* Text Domain: clea-strong-testimonials-add-on
* 
* @package		clea-strong-testimonials-add-on
* @version		0.3
* @author 		Anne-Laure Delpech
* @copyright 	Copyright (c) 2016 Anne-Laure Delpech
* @link			https://github.com/aldelpech/CLEA-presentation
* @license 		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
* @since 		0.1.0
*/
 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Path to files
define( 'CLEA_STRONG_TESTIMONIALS_ADD_ON_FILE', __FILE__ );
define( 'CLEA_STRONG_TESTIMONIALS_ADD_ON_BASENAME', plugin_basename( CLEA_STRONG_TESTIMONIALS_ADD_ON_FILE ));
define( 'CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_PATH', plugin_dir_path( CLEA_STRONG_TESTIMONIALS_ADD_ON_FILE ));
define( 'CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_URL', plugin_dir_url( CLEA_STRONG_TESTIMONIALS_ADD_ON_FILE ));


// charger des styles, fonts ou scripts correctement
require_once CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_PATH . 'includes/clea-strong-testimonials-add-on-enqueues.php'; 

// internationalisation de l'extension
require_once CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_PATH . 'includes/clea-strong-testimonials-add-on-i18n.php'; 

// do the job : add testimonial taxonomy. 
require_once CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_PATH . 'includes/clea-strong-testimonials-add-on-add-taxonomy.php'; 	

// create shortcode to filter the query by this taxonomy. 
require_once CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_PATH . 'includes/clea-strong-testimonials-add-on-shortcodes.php'; 


if ( is_admin() ) {
	// the sections and fields data for the settings page. 
	require_once CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_PATH . 'admin/clea-strong-testimonials-add-on-admin-settings.php'; 

	// load styles and scripts for the admin
	require_once CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_PATH . 'admin/clea-strong-testimonials-add-on-admin-enqueue.php'; 

	// Settings page for the admin
	require_once CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_PATH . 'admin/clea-strong-testimonials-add-on-settings-page.php'; 
}

// add a new taxonomy to strong testimonials
add_action( 'init', 'clea_ib_add_taxonomy_to_strong_testimonial', 11 );	

/*
// for admin
add_action( 'admin_enqueue_scripts',  'clea_strong_testimonials_add_on_admin_enqueue_scripts' );

// default "orientation" for a new testimonial
add_action( 'save_post_wpm-testimonial', 'clea_ib_default_tax_slug_strong_testimonials' );

// functions for strong testimonials orientation taxonomy
add_filter( 'wpmtst_query_args', 'clea_ib_strong_testimonials_query_args' );

// create the settings page and it's menu
add_action( 'admin_menu', 'clea_strong_testimonials_add_on_admin_menu', 11 );

// set the content of the admin page
add_action( 'admin_init', 'clea_strong_testimonials_add_on_admin_init' );
*/

/******************************************************************************
* create new "orientation" taxonomy
******************************************************************************/
function clea_ib_add_taxonomy_to_strong_testimonial() {

	// for 'post_types' => array( 'wpm-testimonial' ),
	$labels = array(
		'name'              => __( 'Orientations', 'clea-2-IB' ),
		'singular_name'     => __( 'Orientation', 'clea-2-IB' ),
		'search_items'      => __( 'Chercher dans les Orientations', 'clea-2-IB' ),
		'all_items'         => __( 'Toutes les orientations', 'clea-2-IB' ),
		'edit_item'         => __( 'Modifier l\'orientation', 'clea-2-IB' ),
		'update_item'       => __( 'Mettre à jour l\'orientation', 'clea-2-IB' ),
		'add_new_item'      => __( 'Ajouter une nouvelle  orientation', 'clea-2-IB' ),
		'new_item_name'     => __( 'Nom de la nouvelle orientation', 'clea-2-IB' ),
		'menu_name'         => __( 'Orientations', 'clea-2-IB' ),
	);

	$capa = array (
		'manage_terms' => 'manage_options', //by default only admin
		'edit_terms' => 'manage_options',
		'delete_terms' => 'manage_options',
		'assign_terms' => 'edit_posts'  // means administrator', 'editor', 'author', 'contributor'
	) ;
	
	$args = array(
		'labels' => $labels,
		'hierarchical' 		=> true,
		'sort' 				=> true,
		'args' 				=> array( 'orderby' => 'term_order' ),
		'rewrite' 			=> array( 'slug' => 'orientation-tag' ),
		'show_admin_column' => true,
		'default_term'		=> 'orientation-complet',
		'capabilities'		=> $capa
	);



    // register the taxonomy
    register_taxonomy( 'orientation', 'wpm-testimonial', $args );
	
}



/*----------------------------------------------------------------------------*
 * Activation 
* @since 0.1
 *----------------------------------------------------------------------------*/	
function clea_strong_testimonials_add_on_activation() {

	// Before activating, check if strong_testimonials is active
	if( ! class_exists( 'Strong_Testimonials' ) ) {
		// strong-testimonials is not active
		add_action( 'admin_notices', 'clea_strong_testimonials_add_on_notice__error' );
		exit ;
	}
	
	clea_ib_add_taxonomy_to_strong_testimonial() ;
	// flush_rewrite_rules();

}

register_activation_hook(__FILE__, 'clea_strong_testimonials_add_on_activation');
	
/*----------------------------------------------------------------------------*
 * deactivation and uninstall
 *----------------------------------------------------------------------------*/
/* upon deactivation, wordpress also needs to rewrite the rules */
register_deactivation_hook(__FILE__, 'clea_strong_testimonials_add_on_deactivation');


function clea_strong_testimonials_add_on_deactivation() {

	flush_rewrite_rules();
	
}

// register uninstaller
register_uninstall_hook(__FILE__, 'clea_strong_testimonials_add_on_uninstall');

function clea_strong_testimonials_add_on_uninstall() {    
	// actions to perform once on plugin uninstall go here
	// remove all options and custom tables
	
	$option_name = 'clea_strong_testimonials_add_on';
 
	delete_option( $option_name );
	 
	// For site options in Multisite
	delete_site_option( $option_name );  
	
}

/*----------------------------------------------------------------------------*
 * if conditions were not met to init the plugin
 *----------------------------------------------------------------------------*/
function clea_strong_testimonials_add_on_notice__error() {
	$class = 'notice notice-error';
	$message = __( "Erreur : l'extension Strong Testimonials doit être installée et activée.", 'clea-strong-testimonials-add-on' );

	printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
}

function clea_strong_testimonials_add_on_disable_plugin() {
	
	// thanks to https://www.sitepoint.com/preventing-wordpress-plugin-incompatibilities/
    if ( current_user_can('activate_plugins') && is_plugin_active( plugin_basename( __FILE__ ) ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );

        // Hide the default "Plugin activated" notice
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }	
}
?>