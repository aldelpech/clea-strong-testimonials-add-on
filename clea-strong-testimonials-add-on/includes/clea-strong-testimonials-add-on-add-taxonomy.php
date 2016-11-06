<?php
/**
 *
 * add a new taxonomy to strong testimonials custom post types
 *
 *
 * @link       	https://github.com/aldelpech/clea-strong-testimonials-add-on
 * @since      	0.2.0
 *
 * @package    clea-strong-testimonials-add-on
 * @subpackage clea-strong-testimonials-add-on/includes
 * Text Domain: clea-strong-testimonials-add-on
 */

 
// add a new taxonomy to strong testimonials
add_action( 'init', 'clea_ib_add_taxonomy_to_strong_testimonial', 11 );	

// default "orientation" for a new testimonial
add_action( 'save_post_wpm-testimonial', 'clea_ib_default_tax_slug_strong_testimonials' );

// functions for strong testimonials orientation taxonomy
add_filter( 'wpmtst_query_args', 'clea_ib_strong_testimonials_query_args' );



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
	
	$args = array(
		'labels' => $labels,
		'hierarchical' 		=> true,
		'sort' 				=> true,
		'args' 				=> array( 'orderby' => 'term_order' ),
		'rewrite' 			=> array( 'slug' => 'orientation-tag' ),
		'show_admin_column' => true,
		'default_term'		=> 'orientation-complet'
	);
    // register the taxonomy
    register_taxonomy( 'orientation', 'wpm-testimonial', $args );
	
}

/******************************************************************************
* set specific terms to specific pages
******************************************************************************/
function clea_ib_strong_testimonials_query_args( $args ) {
	/* using the term ID: */
	
	/*
	$args['tax_query'] = array(
		array(
			'taxonomy' => 'orientation',
			'field'    => 'id',
			'terms'    => 123
		)
	);
	*/
	if ( is_page( 914 ) ) {
		
		$orientation_tag_slug = 'orientation-isabelle' ;
	} else {
		
		$orientation_tag_slug = 'orientation-complet' ;
		
	}
	/* using the term slug: */
	$args['tax_query'] = array(
		array(
			'taxonomy' => 'orientation',
			'field'    => 'slug',
			'terms'    => $orientation_tag_slug
		)
	);	
	return $args;
}

/******************************************************************************
* Set default "orientation" for any new testimonial
******************************************************************************/
function clea_ib_default_tax_slug_strong_testimonials( $post_id ){
		
	// http://wordpress.stackexchange.com/questions/7168/how-to-add-a-default-item-to-a-custom-taxonomy
	// will set the default orientation taxonomy term to "orientation-complet" for 
	// all Strong testimonial custom-post-types (wpm-testimonial) 
	$current_post = get_post( $post_id );
	// This makes sure the taxonomy is only set when a new post is created
	if ( $current_post->post_date == $current_post->post_modified ) {
		wp_set_object_terms( $post_id, 'orientation-complet', 'orientation', true );
	}		
}
