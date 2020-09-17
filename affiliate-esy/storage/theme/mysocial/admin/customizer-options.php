<?php
/**
 * Defines customizer options
 *
 * @package Customizer Library Demo
 */

function customizer_library_demo_options() {

	// Theme defaults
	$primary_color = '#26b';
	$secondary_color = '#48d';

	// Stores all the controls that will be added
	$options = array();

	// Stores all the sections to be added
	$sections = array();

	// Stores all the panels to be added
	$panels = array();

	// Adds the sections to the $options array
	$options['sections'] = $sections;

	// More Examples
	$section = 'examples';

	// Arrays 

	$layout_choices = array(
		'choice-1' => 'Responsive Layout',
		'choice-2' => 'Fixed Layout'
	);

	$sections[] = array(
		'id' => $section,
		'title' => __( 'Theme Settings', 'mysocial' ),
		'priority' => '10'
	);

	$options['logo'] = array(
		'id' => 'logo',
		'label'   => __( 'Logo', 'mysocial' ),
		'section' => $section,
		'type'    => 'image',
		'default' => get_template_directory_uri().'/assets/img/logo.png'
	);

	$options['favicon'] = array(
		'id' => 'favicon',
		'label'   => __( 'Favicon', 'mysocial' ),
		'section' => $section,
		'type'    => 'image',
		'default' => ''
	);	

	$options['site-layout'] = array(
		'id' => 'site-layout',
		'label'   => __( 'Site Layout', 'mysocial' ),
		'section' => $section,
		'type'    => 'radio',
		'choices' => $layout_choices,
		'default' => 'choice-1'
	);

	$options['header-search-on'] = array(
		'id' => 'header-search-on',
		'label'   => __( 'Display header search form', 'mysocial' ),
		'section' => $section,
		'type'    => 'checkbox',
		'default' => true,
	);	

	$options['primary-nav-heading'] = array(
		'id' => 'primary-nav-heading',
		'label'   => __( 'Mobile Primary Menu Heading Text', 'mysocial' ),
		'section' => $section,
		'type'    => 'text',
		'default' => __('Pages', 'mysocial'),
	);

	$options['secondary-nav-heading'] = array(
		'id' => 'secondary-nav-heading',
		'label'   => __( 'Mobile Secondary Menu Heading Text', 'mysocial' ),
		'section' => $section,
		'type'    => 'text',
		'default' => __('Categories', 'mysocial'),
	);					

	$options['entry-excerpt-length'] = array(
		'id' => 'entry-excerpt-length',
		'label'   => __( 'Number of words to show on excerpt', 'mysocial' ),
		'section' => $section,
		'type'    => 'text',
		'default' => '20',		
	);

	$options['single-featured-on'] = array(
		'id' => 'single-featured-on',
		'label'   => __( 'Display featured image on single posts', 'mysocial' ),
		'section' => $section,
		'type'    => 'checkbox',
		'default' => false,
	);		

	$options['footer-widgets-on'] = array(
		'id' => 'footer-widgets-on',
		'label'   => __( 'Display footer widgets', 'mysocial' ),
		'section' => $section,
		'type'    => 'checkbox',
		'default' => 1,
	);	

	//$options['example-range'] = array(
	//	'id' => 'example-range',
	//	'label'   => __( 'Example Range Input', 'mysocial' ),
	//	'section' => $section,
	//	'type'    => 'range',
	//	'input_attrs' => array(
	//      'min'   => 0,
	//        'max'   => 10,
	//        'step'  => 1,
	//       'style' => 'color: #0a0',
	//	)
	//);

	// Adds the sections to the $options array
	$options['sections'] = $sections;

	// Adds the panels to the $options array
	$options['panels'] = $panels;

	$customizer_library = Customizer_Library::Instance();
	$customizer_library->add_options( $options );

	// To delete custom mods use: customizer_library_remove_theme_mods();

}
add_action( 'init', 'customizer_library_demo_options' );