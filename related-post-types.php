<?php defined( 'ABSPATH' ) or die( 'This script cannot be accessed directly.' );

/**
 * Plugin Name: Related Post Types for Impreza Grids
 * Description: Show certain post types in related posts Grid
 * Version:     1.0.0
 * Author:      Leeming Design
 * Author URI:  https://leemingdesign.co.uk/
 * License:     Leeming Design
 */


if ( ! function_exists( 'post_type_for_related_posts_config' ) ) {
	add_filter( 'us_config_elements/grid', 'post_type_for_related_posts_config', 1 );
	add_filter( 'us_config_elements/carousel', 'post_type_for_related_posts_config', 1 );
	/**
	 * Update configuration for the related posts post type
	 *
	 * @param array $config The original configuration array.
	 * @return array The updated configuration array.
	 */
	function post_type_for_related_posts_config( $config ) {
		$params = $config['params'];

		$same_tax_certain_post_type = array(
			'same_tax_certain_post_type' => array(
				'title' => 'Post Type',
				'type' => 'checkboxes',
				'options' => us_grid_available_post_types(),
				'std' => '',
				'show_if' => array(
					'post_type',
					'=',
					'related',
				),
			),
		);

		$params = array_slice( $params, 0, 3, TRUE )
			+ $same_tax_certain_post_type
			+ array_slice( $params, 3, count( $params ) - 1, TRUE );

		$config['params'] = $params;

		return $config;
	}
}

if ( ! function_exists( 'post_type_for_related_posts' ) ) {
	add_filter( 'us_template_vars:templates/us_grid/listing', 'post_type_for_related_posts' );
	/**
	 * Sets the post type for related posts in Grid/Carousel
	 *
	 * @param array $vars The array containing the variables.
	 * @return array The modified array with the post type set for related posts.
	 */
	function post_type_for_related_posts( $vars ) {
		if (
			! empty( $vars['post_type'] )
			AND $vars['post_type'] === 'related'
			AND ! empty( $vars['same_tax_certain_post_type'] )
		) {
			$post_types = explode( ',', $vars['same_tax_certain_post_type'] );
			$vars['query_args']['post_type'] = $post_types;
		}

		return $vars;
	}
}
