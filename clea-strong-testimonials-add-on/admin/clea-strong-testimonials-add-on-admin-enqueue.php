<?php
/**
 *
 * Enqueue styles and scripts for the admin settings page

 *
 * @link       	
 * @since      	0.2.0
 *
 * @package    clea-strong-testimonials-add-on
 * @subpackage clea-strong-testimonials-add-on/admin
 * Text Domain: clea-strong-testimonials-add-on
 */
 
add_action( 'admin_enqueue_scripts',  'clea_strong_testimonials_add_on_admin_enqueue_scripts' );

function clea_strong_testimonials_add_on_admin_enqueue_scripts( $hook ) {
	

	// to find the right name, go to the settings page and inspect it
	// the name is somewhere in the <body class="">
	// it will always begin with settings_page_
	if( 'settings_page_clea-strong-testimonials-add-on' != $hook ) { 
        echo "not the right page, this is : " ;
		echo $hook ;
		return;
		
    }

	// for the alpha color picker
	// source : https://github.com/BraadMartin/components/tree/master/alpha-color-picker
    wp_enqueue_style(
        'alpha-color-picker',
        CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_URL . 'admin/css/alpha-color-picker.css', 
        array( 'wp-color-picker' ) // You must include these here.
    );

	wp_enqueue_script(
        'alpha-color-picker',
        CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_URL . 'admin/js/alpha-color-picker.js', 
        array( 'jquery', 'wp-color-picker' ), // You must include these here.
        null,
        true
    );
	
    // This is the JS file that will contain the trigger script.
    // Set alpha-color-picker as a dependency here.
    wp_enqueue_script(
        'clea-add-button-admin-color-js',
        CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_URL . 'admin/js/clea-strong-testimonials-add-on-color-trigger.js', 
        array( 'alpha-color-picker' ),
        null,
        true
    );

	// options page style
    wp_enqueue_style(
        'clea-strong-testimonials-add-on-admin-style',
        CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_URL . 'admin/css/clea-strong-testimonials-add-on-admin.css'
    );

	
} 



