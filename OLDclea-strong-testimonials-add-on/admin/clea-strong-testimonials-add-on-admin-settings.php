<?php
/**
 *
 * Les champs et sections à mettre dans la page de réglages de l'extension
 * Attention ce fichier doit absolument étre encodé en UTF8 si on veut des accents. 
 * 
 * 
 * @link       	
 * @since      	0.3.0
 *
 * @package    clea-strong-testimonials-add-on
 * @subpackage clea-strong-testimonials-add-on/admin
 * Text Domain: clea-strong-testimonials-add-on
 */

/**********************************************************************

* THE SECTIONS

**********************************************************************/
function clea_strong_testimonials_add_on_settings_sections_val() {

	$sections = array(
		array(
			'section_name' 	=> 'section-1', 
			'section_title'	=>  __( 'Orientation par défaut', 'clea-strong-testimonials-add-on' ), 
			'section_callbk'=> 'clea_strong_testimonials_add_on_settings_section_callback', 
			'menu_slug'		=> 'clea-strong-testimonials-add-on' ,								
		),
		array(
			'section_name' 	=> 'section-2',
			'section_title'	=>  __( 'Affectation / pages', 'clea-strong-testimonials-add-on' ),
			'section_callbk'=> 'clea_strong_testimonials_add_on_settings_section_callback' ,
			'menu_slug'		=> 'clea-strong-testimonials-add-on'
			),
		array(
			'section_name' 	=> 'section-3',
			'section_title'	=>  __( 'Shortcodes', 'clea-strong-testimonials-add-on' ),
			'section_callbk'=> 'clea_strong_testimonials_add_on_settings_section_3' ,
			'menu_slug'		=> 'clea-strong-testimonials-add-on'
			),
	);	
	
	return $sections ;
	
}


/**********************************************************************

* THE FIELDS

**********************************************************************/
function clea_strong_testimonials_add_on_settings_fields_val() {

	$section_1_fields = array (
		array(
			'field_id' 		=> 'default_orientation', 							
			'label'			=> __( 'Orientation par défaut', 'clea-strong-testimonials-add-on' ), 	
			'field_callbk'	=> 'clea_strong_testimonials_add_on_default_orientation', 					
			'menu_slug'		=> 'clea-strong-testimonials-add-on', 							
			'section_name'	=> 'section-1',
			'type'			=> 'select',
			'helper'		=> __( 'Cette orientation sera affectée par défaut à tous les témoignages', 'clea-strong-testimonials-add-on' ),
			'default'		=> ''			
		),
	) ;

	
	$section_2_fields = array () ;

	// find all terms for the orientation taxonomy
	$orientation_terms = get_terms( array(
		'taxonomy' 	=> 'orientation', 
		'hide_empty'	=> false, 
		'fields'	=> 'all' 
	) );

	foreach( $orientation_terms as $term) {
		
		// a different name and field id for each orientation
		$name = "p_" . $term->name  ;
		$field = "p_" . $term->slug  ;
		
		$section_2_fields[] = array(
			'field_id' 		=> $field, 
			'label'			=> $name,  
			'field_callbk'	=> 'clea_strong_testimonials_add_on_assign_page', 
			'menu_slug'		=> 'clea-strong-testimonials-add-on', 
			'section_name'	=> 'section-2',
			'type'			=> 'select',
			'helper'		=> '',
			'default'		=> '',			
		) ;
	}

	$section_fields = array(
		'section-1'	=> $section_1_fields,
		'section-2' => $section_2_fields
	) ;	

	
	
	return $section_fields ;
}

?>