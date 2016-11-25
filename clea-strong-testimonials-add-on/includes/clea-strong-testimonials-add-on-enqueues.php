<?php
/**
 *
 * Charger styles et scripts du plugin
 *
 *
 * @link       	https://github.com/aldelpech/clea-strong-testimonials-add-on
 * @since      	0.1.0
 *
 * @package    clea-strong-testimonials-add-on
 * @subpackage clea-strong-testimonials-add-on/includes
 * Text Domain: clea-strong-testimonials-add-on
 */

 
function clea_strong_testimonials_add_on_enqueue_scripts() {

	// feuille de style pour les témoignages (non spécifiques à un site )
	wp_enqueue_style( 'clea-strong-testimonials-add-on', CSTAO_DIR_URL . '/css/clea-strong-testimonials-style.css', array(), false, 'all' );

}
?>