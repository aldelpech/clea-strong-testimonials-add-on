﻿<?php
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

define('ENABLE_DEBUG', false );	// if true, the script will echo debug data

/**********************************************************************

* to set the title of the setting page see -- clea_strong_testimonials_add_on_options_page()
* to set the section rendering see -- clea_strong_testimonials_add_on_settings_section_callback( $args  )
* to set the array of sections see -- clea_strong_testimonials_add_on_settings_sections_val()
* to set the fields rendering see -- clea_strong_testimonials_add_on_settings_field_callback( $arguments  )
* to set the array of fields see -- clea_strong_testimonials_add_on_settings_fields_val()

**********************************************************************/

// create the settings page and it's menu
add_action( 'admin_menu', 'clea_strong_testimonials_add_on_admin_menu' );

// set the content of the admin page
add_action( 'admin_init', 'clea_strong_testimonials_add_on_admin_init' );


function clea_strong_testimonials_add_on_admin_menu() {
	
    add_options_page( 
		__('Options de Clea Add Button', 'clea-strong-testimonials-add-on' ),	// page title (H1)	
		__('Clea plugin boilerplate', 'clea-strong-testimonials-add-on' ),		// menu title
		'manage_options', 												// required capability
		'clea-strong-testimonials-add-on', 										// menu slug (unique ID)
		'clea_strong_testimonials_add_on_options_page' );						// callback function
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
		<h2><?php _e('My Plugin Options', 'clea-strong-testimonials-add-on'); ?></h2>
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

		'section-1' 	=> __( 'Text regarding Section One goes here.', 'clea-strong-testimonials-add-on' ),
		'section-2' 	=> __( 'Text regarding Section Two goes here.', 'clea-strong-testimonials-add-on' )
	);		

	$description = $sect_descr[ $args['id'] ] ;
	printf( '<span class="section-description">%s<span>', $description );

	if ( ENABLE_DEBUG ) {
		
		if ( is_plugin_active( 'query-monitor-extension-checking-variables/query-monitor-check-var.php' ) ) {
		  	
			console( $args );	
			
		} else {
			echo " ---- NOT WORKING ---------------------------------" ;
		}

	}
}

/**********************************************************************

* Field callback

**********************************************************************/

function clea_strong_testimonials_add_on_settings_field_callback( $arguments  ) {


	$settings = (array) get_option( "clea-strong-testimonials-add-on-settings" );
	$field = $arguments[ 'field_id' ] ;
	
	// for development only
	if ( ENABLE_DEBUG ) {
		
		echo "<hr /><p>Arguments</p><pre>";
		print_r( $arguments ) ;	
		echo "</pre><hr />";
		echo "<hr /><p>Options</p><pre>";
		print_r( $settings ) ;	
		echo "</pre><hr />";
	}
		
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
        printf( ' %s', $description ); // Show it
    }

	printf( '</span>' ) ;

	$name = 'clea-strong-testimonials-add-on-settings['. $field . ']' ;

	switch( $arguments['type'] ){
		
		case 'text' : 
			printf( '<input type="text" id="%3$s" name="%2$s" value="%1$s" />', esc_attr( $value ), $name, $field );
			break ;
		case 'textarea' : 
			printf( '<textarea name="%2$s" id="%3$s" rows="4" cols="80" value="%1$s">%1$s</textarea>', esc_textarea( $value ), $name, $field );
			break ;
		case 'select': // If it is a select dropdown

		if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
			
			printf( '<select id="%2$s" name="%1$s">', $name, $field );
			foreach( $arguments['options'] as $item ) {
				$selected = ( $value	 == $item ) ? 'selected="selected"' : '';
				echo "<option value='$item' $selected>$item</option>";
			}
			echo "</select>";	
			
			} else {
				echo __( 'Indiquer les options dans la définition du champs', 'clea-strong-testimonials-add-on' ) ;
			}
			break;

			case 'checkbox' : 
			printf( '<input type="hidden" name="%1$s" id="%2$s" value="0" />', $name, $field ) 	;
			$checked = '' ;
			if( $value ) { $checked = ' checked="checked" ' ; }
			printf( ' <input %3$s id="%2$s" name="%1$s" type="checkbox" />', $name, $field, $checked ) ;
			break ;

			case 'radio' : // radio buttons
			if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
				
				echo "<span class='radio'>" ;
				foreach( $arguments['options'] as $key => $label ){
					
					$items[] = $label ;
				}

				foreach( $arguments['options'] as $item) {
					$checked = ( $value == $item ) ? 'checked' : '';
					printf( '<label><input type="radio" value="%2$s" name="%1$s" %3$s> %2$s</label><br />', $name, $item, $checked );
				}
				echo "</span>" ;
			}
			break ;

			case 'wysiwig' :
		
			// sanitize data for the wp_editor
			$content = wp_kses_post( $value ) ;
			
			$args = array(
				'textarea_name' => $name
			) ;
			
			wp_editor( $content, $field, $args );		
			break ;	

			case 'color' :
	
			// get the colors used by the theme
			$current_colors = clea_presentation_get_current_colors() ;
			$data_palette = "";
			
			// the color palette must be a string with colors and | separator
			// "#222|#444|#00CC22|rgba(72,168,42,0.4)" would be ok
			foreach ( $current_colors as $color ) {
				
				$data_palette .= $color . '|' ;
				
			}
			
			$data_palette = rtrim( $data_palette, '|' ) ;
			
			
			// uses https://github.com/BraadMartin/components/tree/master/alpha-color-picker
			printf( '<input type="text" class="alpha-color-picker" name="%2$s" value="%1$s" data-palette="%5$s" data-default-color="%4$s" data-show-opacity="true" />', sanitize_text_field( $value ), $name, $field, $arguments['default'], $data_palette  ) ;
			break ;				

		case 'date-picker' : 
			printf( '<input type="date" id="%3$s" name="%2$s" value="%1$s" class="datepicker" />', sanitize_text_field( $value ), $name, $field, $arguments['default'] ) ;
			break ;
			
		case 'email' :
			
			printf( '<input type="text" id="%3$s" name="%2$s" value="%1$s" />', sanitize_email( $value ), $name, $field );
			break ;
		
		case 'url' :
			$content = sanitize_text_field( $value ) ;
			printf( '<input type="text" id="%3$s" name="%2$s" value="%1$s" />', esc_url( $content ), $name, $field );			
			break ;
			
		default : 
			printf( esc_html__( 'This field is type <em>%s</em> and could not be rendered.', 'clea-strong-testimonials-add-on' ), $arguments['type']  );
			
	}
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

/**********************************************************************

* find the colors used in the website's theme

**********************************************************************/
function clea_strong_testimonials_add_on_get_current_colors() {

	// to get all the current theme data in an array
	$mods = get_theme_mods();

	$color = array() ;
	
	foreach ( $mods as $key => $values ) {
		
		if ( !is_array( $values ) ) {
			
			if ( is_string( $values ) && trim( $values ) != '' ) {
				
				$hex = sanitize_hex_color_no_hash( $values ) ;
				
				if ( trim( $hex ) != '' ) {
					
					$color[ $key ] = $hex ;

				}

			}
		} 
	}

	// remove duplicate colors
	$colors = array_unique( $color ) ;
	
	return $colors ;
	
}

