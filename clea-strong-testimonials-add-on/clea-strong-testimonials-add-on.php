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
* @since 		0.1.0   f
*/
 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Path to files
define( 'CSTAO_FILE', __FILE__ );
define( 'CSTAO_BASENAME', plugin_basename( CSTAO_FILE ));
define( 'CSTAO_DIR_PATH', plugin_dir_path( CSTAO_FILE ));
define( 'CSTAO_DIR_URL', plugin_dir_url( CSTAO_FILE ));

/***** LOAD everything for front end *******/

// charger des styles, fonts ou scripts correctement
require_once CSTAO_DIR_PATH . 'includes/clea-strong-testimonials-add-on-enqueues.php'; 

// internationalisation de l'extension
require_once CSTAO_DIR_PATH . 'includes/clea-strong-testimonials-add-on-i18n.php'; 

// do the job : add testimonial taxonomy. 
require_once CSTAO_DIR_PATH . 'includes/clea-strong-testimonials-add-on-add-taxonomy.php'; 	

add_action( 'plugins_loaded', 'clea_ib_check_strong_testimonial' );

// add a new taxonomy to strong testimonials
add_action( 'init', 'clea_ib_add_taxonomy_to_strong_testimonial', 11 );	

add_action( 'plugins_loaded', 'clea_strong_testimonials_add_on_load_plugin_textdomain' );

// default "orientation" for a new testimonial
add_action( 'save_post_wpm-testimonial', 'clea_ib_default_tax_slug_strong_testimonials' );

// functions for strong testimonials orientation taxonomy
add_filter( 'wpmtst_query_args', 'clea_ib_strong_testimonials_query_args' );

// load front end styles and scripts
add_action( 'wp_enqueue_scripts', 'clea_strong_testimonials_add_on_enqueue_scripts' ); 

/***** LOAD everything for ADMIN *******/

if ( is_admin() ) {
	// the sections and fields data for the settings page. 
	require_once CSTAO_DIR_PATH . 'admin/clea-strong-testimonials-add-on-admin-settings.php'; 

	// load styles and scripts for the admin
	require_once CSTAO_DIR_PATH . 'admin/clea-strong-testimonials-add-on-admin-enqueue.php'; 

	// Settings page for the admin
	require_once CSTAO_DIR_PATH . 'admin/clea-strong-testimonials-add-on-settings-page.php'; 

	// create the settings page and it's menu
	add_action( 'admin_menu', 'clea_strong_testimonials_add_on_admin_menu', 11 );

	// set the content of the admin page
	add_action( 'admin_init', 'clea_strong_testimonials_add_on_admin_init', 11 );

	// for admin
	add_action( 'admin_enqueue_scripts',  'clea_strong_testimonials_add_on_admin_enqueue_scripts' );
	
}

/******************************************************************************
* create new "orientation" taxonomy
******************************************************************************/
function clea_ib_add_taxonomy_to_strong_testimonial() {


	// only if strong testimonials post types exist
	if ( post_type_exists( 'wpm-testimonial' ) ) {

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
}


function clea_ib_check_strong_testimonial() {

	// Before activating, check if strong_testimonials is active
	if( !  class_exists( 'Strong_Testimonials' ) ) {

		// strong-testimonials is not active
		add_action( 'admin_notices', 'clea_strong_testimonials_add_on_notice__error' );

    }	
	
}


register_activation_hook(__FILE__, 'clea_strong_testimonials_add_on_activation');
register_deactivation_hook(__FILE__, 'clea_strong_testimonials_add_on_deactivation');

/*----------------------------------------------------------------------------*
 * Activation 
* @since 0.1
 *----------------------------------------------------------------------------*/	
function clea_strong_testimonials_add_on_activation() {

	clea_ib_add_taxonomy_to_strong_testimonial() ;
	flush_rewrite_rules();
	
}

register_uninstall_hook(__FILE__, 'clea_strong_testimonials_add_on_uninstall');
	
/*----------------------------------------------------------------------------*
 * deactivation and uninstall
 *----------------------------------------------------------------------------*/
function clea_strong_testimonials_add_on_deactivation() {

	flush_rewrite_rules();
	
}

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

?>