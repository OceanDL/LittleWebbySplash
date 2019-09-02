<?php
/**
 * Add Jupiter settings for Site Identity > Logo > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_logo';

// Primary Logo.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-image',
	'settings' => 'jupiterx_logo',
	'section'  => $section,
	'label'    => __( 'Primary Logo', 'jupiterx' ),
	'column'   => 4,
] );

// Secondary Logo.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-image',
	'settings' => 'jupiterx_logo_secondary',
	'section'  => $section,
	'label'    => __( 'Secondary Logo', 'jupiterx' ),
	'column'   => 4,
] );

// Sticky Logo.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-image',
	'settings' => 'jupiterx_logo_sticky',
	'section'  => $section,
	'label'    => __( 'Sticky Logo', 'jupiterx' ),
	'column'   => 4,
] );

// Retina Primary Logo.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-image',
	'settings' => 'jupiterx_logo_retina',
	'section'  => $section,
	'label'    => __( 'Retina Primary Logo', 'jupiterx' ),
	'column'   => 4,
] );

// Retina Secondary Logo.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-image',
	'settings' => 'jupiterx_logo_secondary_retina',
	'section'  => $section,
	'label'    => __( 'Retina Secondary Logo', 'jupiterx' ),
	'column'   => 4,
] );

// Retina sticky logo.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-image',
	'settings' => 'jupiterx_logo_sticky_retina',
	'section'  => $section,
	'label'    => __( 'Retina Sticky Logo', 'jupiterx' ),
	'column'   => 4,
] );

// Mobile Logo.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-image',
	'settings' => 'jupiterx_logo_mobile',
	'section'  => $section,
	'label'    => __( 'Mobile Logo', 'jupiterx' ),
	'column'   => 4,
] );


// Retina Mobile Logo.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-image',
	'settings' => 'jupiterx_logo_mobile_retina',
	'section'  => $section,
	'label'    => __( 'Retina Mobile Logo', 'jupiterx' ),
	'column'   => 4,
] );
