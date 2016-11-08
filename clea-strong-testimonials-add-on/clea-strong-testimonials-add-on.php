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

/******************************************************************************
* Before loading, check if strong_testimonials is active
* @since 0.1 
******************************************************************************/


add_action( 'init', 'clea_strong_testimonials_add_on_init' );

function clea_strong_testimonials_add_on_init() {

	if( class_exists( 'Strong_Testimonials' ) ) {
		// strong-testimonials is active
		add_action( 'init', 'clea_strong_testimonials_add_on_setup' );
	} else {
		
		// notify user_error
		add_action( 'admin_init', 'clea_strong_testimonials_add_on_disable_plugin' );
		add_action( 'admin_notices', 'clea_strong_testimonials_add_on_notice__error' );
	}

}
	
/******************************************************************************
* Install plugin 
* @since 0.2 
******************************************************************************/

function clea_strong_testimonials_add_on_setup() {

	// Path to files
	define( 'CLEA_STRONG_TESTIMONIALS_ADD_ON_FILE', __FILE__ );
	define( 'CLEA_STRONG_TESTIMONIALS_ADD_ON_BASENAME', plugin_basename( CLEA_STRONG_TESTIMONIALS_ADD_ON_FILE ));
	define( 'CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_PATH', plugin_dir_path( CLEA_STRONG_TESTIMONIALS_ADD_ON_FILE ));
	define( 'CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_URL', plugin_dir_url( CLEA_STRONG_TESTIMONIALS_ADD_ON_FILE ));
			
	/* appeler d'autres fichiers php et les exécuter
	* @since 0.1
	*/	
		
	// charger des styles, fonts ou scripts correctement
	require_once CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_PATH . 'includes/clea-strong-testimonials-add-on-enqueues.php'; 

	// internationalisation de l'extension
	require_once CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_PATH . 'includes/clea-strong-testimonials-add-on-i18n.php'; 

	// Settings page for the admin
	require_once CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_PATH . 'admin/clea-strong-testimonials-add-on-settings-page.php'; 

	// load styles and scripts for the admin
	require_once CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_PATH . 'admin/clea-strong-testimonials-add-on-admin-enqueue.php'; 

	// the sections and fields data for the settings page. 
	require_once CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_PATH . 'admin/clea-strong-testimonials-add-on-admin-settings.php'; 
	
	// do the job : add testimonial taxonomy. 
	require_once CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_PATH . 'includes/clea-strong-testimonials-add-on-add-taxonomy.php'; 	

	// create shortcode to filter the query by this taxonomy. 
	require_once CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_PATH . 'includes/clea-strong-testimonials-add-on-shortcodes.php'; 	
} 






/*----------------------------------------------------------------------------*
 * Activation 
 *----------------------------------------------------------------------------*/	
function clea_strong_testimonials_add_on_activation() {

}

register_activation_hook(__FILE__, 'clea_strong_testimonials_add_on_activation');
	
/*----------------------------------------------------------------------------*
 * deactivation and uninstall
 *----------------------------------------------------------------------------*/
/* upon deactivation, wordpress also needs to rewrite the rules */
register_deactivation_hook(__FILE__, 'clea_strong_testimonials_add_on_deactivation');


function clea_strong_testimonials_add_on_deactivation() {
	
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