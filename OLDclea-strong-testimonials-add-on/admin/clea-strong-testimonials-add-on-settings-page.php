<?php
/**
 *
 * Créer une page de settings pour l'extension

 *
 * @link       	
 * @since      	0.2.0
 *
 * @package    clea-strong-testimonials-add-on
 * @subpackage clea-strong-testimonials-add-on/admin
 * Text Domain: clea-strong-testimonials-add-on
 */

//  Based on Anna's gist https://gist.github.com/annalinneajohansson/5290405
// http://codex.wordpress.org/Settings_API


/**********************************************************************
* DEBUG ?
***********************************************************************/

define('AL_ENABLE_DEBUG', false );	// if true, the script will echo debug data


function clea_strong_testimonials_add_on_admin_menu() {

    add_submenu_page( 
		'edit.php?post_type=wpm-testimonial',								// parent slug
		__('CLEA testimonials add-on', 'clea-strong-testimonials-add-on' ),	// page title (H1)	
		__('Clea add-on', 'clea-strong-testimonials-add-on' ),				// menu title
		'manage_options', 													// required capability
		'clea-strong-testimonials-add-on', 									// menu slug (unique ID)
		'clea_strong_testimonials_add_on_options_page' );					// callback function
}

function clea_strong_testimonials_add_on_admin_init() {
  
	if( false == get_option( 'clea-strong-testimonials-add-on-settings' ) ) {  
		add_option( 'clea-strong-testimonials-add-on-settings' );
	}
	
	register_setting( 'my-settings-group', 'clea-strong-testimonials-add-on-settings', 'clea_strong_testimonials_add_on_settings_validate_and_sanitize' ) ;
	
	$set_sections = clea_strong_testimonials_add_on_settings_sections_val() ;
 
	// add_settings_section
	foreach( $set_sections as $section ) {
		
		add_settings_section( 
			$section[ 'section_name' ], 
			$section[ 'section_title' ] ,
			$section[ 'section_callbk' ], 
			$section[ 'menu_slug' ]
		);
		
	}	

	$set_fields = clea_strong_testimonials_add_on_settings_fields_val() ;
	
	// add the fields
	foreach ( $set_fields as $section_field ) {

		foreach( $section_field as $field ){

			add_settings_field( 
				$field['field_id'], 
				$field['label'], 
				$field['field_callbk'],  
				$field['menu_slug'], 
				$field['section_name'],
				$field
			);
		}

	}	
}

/**********************************************************************

* The actual page

**********************************************************************/
function clea_strong_testimonials_add_on_options_page() {
?>
	<div class="wrap">
		<h2><?php _e("CLEA add-on pour Strong Testimonials", 'clea-strong-testimonials-add-on'); ?></h2>
		<form action="options.php" method="POST">
			<?php settings_fields('my-settings-group'); ?>
			<?php do_settings_sections('clea-strong-testimonials-add-on'); ?>
			<?php submit_button(); ?>
		</form>
	
	</div>
<?php }

/**********************************************************************

* Section callback

**********************************************************************/

function clea_strong_testimonials_add_on_settings_section_callback( $args  ) {
	
	$sect_descr = array(

		'section-1' 	=> __( "Définir l'orientation par défaut des nouveaux témoignages.", 'clea-strong-testimonials-add-on' ),
		'section-2' 	=> __( 'Affecter une page à une orientation spécifique.', 'clea-strong-testimonials-add-on' ),
		'section-3' 	=> __( 'Les shortcodes pour chaque orientation.', 'clea-strong-testimonials-add-on' ),
	);		

	$description = $sect_descr[ $args['id'] ] ;
	printf( '<span class="section-description">%s<span>', $description );

}

function clea_strong_testimonials_add_on_settings_section_3( $args  ) {
	
	$sect_descr = array(

		'section-1' 	=> __( "Définir l'orientation par défaut des nouveaux témoignages.", 'clea-strong-testimonials-add-on' ),
		'section-2' 	=> __( 'Affecter une page à une orientation spécifique.', 'clea-strong-testimonials-add-on' ),
		'section-3' 	=> __( 'Les shortcodes pour chaque orientation.', 'clea-strong-testimonials-add-on' ),
	);		

	$description = $sect_descr[ $args['id'] ] ;
	printf( '<span class="section-description">%s<span>', $description );

	/* TEST content here ----------------------------------------------------*/
	$options = clea_ib_default_tax_get_options() ;

	foreach( $options as $key => $term ) {
		echo "<p>" . $key . " | " ;
		if( empty( $term ) ) {
			echo "empty" ;
		} else {
			echo $term ;
		}
		echo "</p>";
	}

	// flip the options array (values become keys and keys become values)
	
	// erase empty values http://www.thecave.info/quickest-way-remove-empty-array-elements-php/
	
	
	// flip the options array (values become keys and keys become values)
	$page_attached = array_filter( $options, function($v){return $v !== '';});
	echo "<hr /><p>page_attached filtered </p><pre>";
	print_r( $page_attached ) ;	
	echo "</pre><hr />";	

	$page_attached = array_flip( $page_attached );
	echo "<hr /><p>page_attached flipped </p><pre>";
	print_r( $page_attached ) ;	
	echo "</pre><hr />";		
	
	
	$current_page_id = 914 ;	

	if ( isset( $page_attached[ $current_page_id ]) ) {

		// this page should not display everything 
		$term = $page_attached[ $current_page_id ] ;
		$slug = preg_replace( '/^p_/', '', $term ); 
	} else {
		$slug = 'orientation-complet' ;
	}	
	echo "<p>" . $slug . "</p>" ;
	/* END TEST content  ----------------------------------------------------*/
	
}

/**********************************************************************

* Field callback

**********************************************************************/
function clea_strong_testimonials_add_on_default_orientation( $arguments  ) {


	$settings = (array) get_option( "clea-strong-testimonials-add-on-settings" );
	$field = $arguments[ 'field_id' ] ;

	// set a $options array with the field id as it's key
	if ( !empty( $settings ) ) {
		foreach ( $settings as $key => $option ) {
			$options[$key] = $option;
		}
	}	
	
	// now check if $options[ $field ] is set
	if( isset( $options[ "$field" ] ) ) {
			$value = $settings[ $field ] ;
	} else {
			// set the value to the default value
			$value = $arguments[ 'default' ] ;
	}	

	// If there is a help text and / or description
	printf( '<span class="field_desc">' ) ;
	
    if( isset( $arguments['helper'] ) && $helper = $arguments['helper'] ){

		printf( '<span class="helper" data-descr="%2$s"><img src="%1$simages/question-mark.png" class="alignleft" id="helper" alt="help" data-pin-nopin="true"></span>',CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_URL, $helper ) ;
    }
	
	// If there is a description
    if( isset( $arguments['label'] ) && $description = $arguments['label'] ){
        // printf( ' %s', $description ); // Show it
    }


	
	printf( '</span>' ) ;

	$name = 'clea-strong-testimonials-add-on-settings['. $field . ']' ;

	// find all terms for the orientation taxonomy
	$orientation_terms = get_terms( array(
		'taxonomy' 	=> 'orientation', 
		'hide_empty'	=> false, 
		'fields'	=> 'all' 
	) );

	// for development only
	if ( AL_ENABLE_DEBUG ) {
		
		echo "<hr /><p>Arguments</p><pre>";
		print_r( $arguments ) ;	
		echo "</pre><hr />";
		echo "<hr /><p>Options</p><pre>";
		print_r( $settings ) ;	
		echo "</pre><hr />";
		echo "<p>value : " . $value . "</p>" ;
		echo "<p>settings : " . $settings[ $field ] . "</p>" ;
		echo "<hr /><p>Options</p><pre>";
		print_r( $orientation_terms ) ;	
		echo "</pre><hr />";
	}		
	
	printf( '<select id="%2$s" name="%1$s">', $name, $field );
		foreach( $orientation_terms as $term ) {
					// a different name and field id for each orientation

			$terme = $term->name  ;
			$id = $term->term_id  ;
			$selected = ( $value == $id ) ? 'selected="selected"' : '';
			printf( '<option value="%1$s" %2$s>%3$s</option>', $id, $selected, $terme ) ;
			}
	echo "</select>";	

}


function clea_strong_testimonials_add_on_assign_page( $arguments  ) {

	$settings = (array) get_option( "clea-strong-testimonials-add-on-settings" );
	$field = $arguments[ 'field_id' ] ;
	
	// set a $options array with the field id as it's key
	if ( !empty( $settings ) ) {
		foreach ( $settings as $key => $option ) {
			$options[$key] = $option;
		}
	}	

	// now check if $options[ $field ] is set
	if( isset( $options[ "$field" ] ) ) {
			$value = $settings[ $field ] ;
	} else {
			// set the value to the default value
			$value = $arguments[ 'default' ] ;
	}	

	// If there is a help text and / or description
	printf( '<span class="field_desc">' ) ;
	
    if( isset( $arguments['helper'] ) && $helper = $arguments['helper'] ){

		printf( '<span class="helper" data-descr="%2$s"><img src="%1$simages/question-mark.png" class="alignleft" id="helper" alt="help" data-pin-nopin="true"></span>',CLEA_STRONG_TESTIMONIALS_ADD_ON_DIR_URL, $helper ) ;
    }
	
	// If there is a description
    if( isset( $arguments['label'] ) && $description = $arguments['label'] ){
        // printf( ' %s', $description ); // Show it
    }

	printf( '</span>' ) ;

	$name = 'clea-strong-testimonials-add-on-settings['. $field . ']' ;

	// will display a dropdown list of pages for each item in the orientation taxonomy
	// http://wordpress.stackexchange.com/questions/175645/get-pages-drop-down-list-for-selection-of-a-page

	printf( '<select id="%2$s" name="%1$s">', $name, $field );
	$pages = get_pages();
	
	// first a "no page attached"
	$selected = ( $value == '' ) ? 'selected="selected"' : '';
	printf( '<option value="%1$s" %2$s>%3$s</option>', $value, $selected, "Pas de page attachée" ) ;

	// now all pages
	foreach ( $pages as $page ) {
		
		$selected = ( $value == $page->ID ) ? 'selected="selected"' : '';
		printf( '<option value="%1$s" %2$s>%3$s</option>', $page->ID, $selected, $page->post_title ) ;

	}
	echo "</select>";			
	
}


/**********************************************************************

* Sanitize and validate

**********************************************************************/

function clea_strong_testimonials_add_on_settings_validate_and_sanitize( $input ) {

	$output = (array) get_option( 'clea-strong-testimonials-add-on-settings' );

	$set_fields = clea_strong_testimonials_add_on_settings_fields_val() ;
	$types = array() ;
	// create an array with field names and field types
	foreach ( $set_fields as $fields ) {

		foreach( $fields as $field ){

			$types[ $field['field_id'] ] = $field['type'] ;

		}

	}	

	// now validate and sanitize each field
	foreach ( $types as $key => $type ) {
		

		switch( $type ){
			
			case 'wysiwig' :
				$output[ $key ] = wp_kses_post( $input[ $key ] ) ;
				break ;	
			case 'email' :
				if ( is_email( $input[ $key ] ) ) {
					
					$output[ $key ] = $input[ $key ];

				} else {
					
					$message = __( 'You have entered an invalid e-mail address in : ', 'clea-strong-testimonials-add-on' ) ;
					$message .= $key ;
					add_settings_error( 'clea-strong-testimonials-add-on-settings', 'invalid-email', $message  );
				}
				break ;
			default : 
				$output[ $key ] = sanitize_text_field( $input[ $key ] ) ;
				
		}

		
	}
		
	return $output;
}

?>