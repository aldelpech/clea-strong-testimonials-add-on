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

 

/******************************************************************************
* set specific terms to specific pages
******************************************************************************/
function clea_ib_strong_testimonials_query_args( $args ) {

	$options = clea_ib_default_tax_get_options() ;

	/* will return something like 
	Array
	(
		[0] => 
		[default_orientation] => 11
		[p_orientation-avant-apres] => 
		[p_orientation-comment] => 72
		[p_orientation-complet] => 
		[p_orientation-isabelle] => 
		[p_orientation-methode] => 
	)
	*/
	

	/**
	* si la page a une id qui n'est pas dans l'array
		alors default tag = 'orientation-complet'
	* sinon 
		alors tag = la key (pregreplaced)
	*/

	// erase empty values http://www.thecave.info/quickest-way-remove-empty-array-elements-php/
	$page_attached = array_filter( $options, function($v){return $v !== '';});
	
	$current_page_id = get_the_ID();	

/*
	echo "<hr /><p>Page_attached array (clea-strong-testimonials-add-on-add-taxonomy.php)</p><pre>";
	print_r( $page_attached ) ;	
	echo "</pre>" ;
	echo "<p>Page ID : " . $current_page_id . "</p><hr />";	
*/
	
	$slugs = array() ;

	foreach( $page_attached as $or => $p_id ) {
		
		// each $or will be like p_orientation-comment where tag is orientation-comment
		
		if( $current_page_id == $p_id ) {
			
			$slug = preg_replace( '/^p_/', '', $or ); 
			array_push( $slugs, $slug )  ;	
			
		}

	}
	
/*
	echo "<hr /><p>Slugs array (clea-strong-testimonials-add-on-add-taxonomy.php)</p><pre>";
	print_r( $slugs ) ;	
	echo "</pre><hr />";	
*/
	
	if ( 0 == count( $slugs ) ) {
		
		$slugs = array( 'orientation-complet' ) ; 
		
	} 


// should be $query = new WP_Query( array( 'tag__in' => array( 37, 47 ) ) );
// voir IMPERATIVEMENT https://codex.wordpress.org/Class_Reference/WP_Query

// !!! cherche wor8190_terms.slug IN ('array-orientation-avant-apres-orientation-comment-orientation-complet-orientation-isabelle-orientation-methode')

	
	// use the term slug: 
	$args['tax_query'] = array(
		array(
			'taxonomy' => 'orientation',
			'field'    => 'slug',
			'terms'    => $slugs,
		)
	);	
	return $args;


}

/******************************************************************************
* Set default "orientation" for any new testimonial
******************************************************************************/
function clea_ib_default_tax_slug_strong_testimonials( $post_id ){
		
	$options = clea_ib_default_tax_get_options() ;
	
	// Get term by id (''term_id'') in orientation taxonomy.
	$default = get_term_by( 'id', $options[ 'default_orientation' ], 'orientation' ) ;
	
	$default_term = esc_html( $default->name ) ;	
	
	// http://wordpress.stackexchange.com/questions/7168/how-to-add-a-default-item-to-a-custom-taxonomy
	// will set the default orientation taxonomy term to "orientation-complet" for 
	// all Strong testimonial custom-post-types (wpm-testimonial) 
	$current_post = get_post( $post_id );
	// This makes sure the taxonomy is only set when a new post is created
	if ( $current_post->post_date == $current_post->post_modified ) {
		wp_set_object_terms( $post_id, $default_term, 'orientation', true );
	}
	
}

/*
* 
/******************************************************************************
* get options 
******************************************************************************/
function clea_ib_default_tax_get_options() {

	// get option value
	$settings = (array) get_option( "clea-strong-testimonials-add-on-settings" );

	// set a $options array with the field id as it's key
	if ( !empty( $settings ) ) {
		foreach ( $settings as $key => $option ) {
			$options[$key] = $option;
		}
	}

	return $options ;
	
}
?>