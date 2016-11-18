<?php
/**
 *
 * charger le bon text domain (internationalisation)
 *
 *
 * @link       	https://github.com/aldelpech/clea-strong-testimonials-add-on
 * @since      	0.1.0
 *
 * @package    clea-strong-testimonials-add-on
 * @subpackage clea-strong-testimonials-add-on/includes
 * Text Domain: clea-strong-testimonials-add-on
 */

function clea_strong_testimonials_add_on_load_plugin_textdomain() {
	
    load_plugin_textdomain( 'clea-strong-testimonials-add-on', FALSE, dirname( CSTAO_BASENAME ) . '/languages' );
	
}
?>