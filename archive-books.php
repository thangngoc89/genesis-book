<?php

//* Remove breadcrumbs
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

//* Force full width content
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

//* Remove entry meta in entry header
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

//* Add 'one-half' class to Entry Header to float it left
add_filter( 'genesis_attr_entry-header', 'sk_genesis_attributes_entry_header' );
/**
 * Add attributes for entry header element.
 *
 * @param array $attributes Existing attributes.
 *
 * @return array Amended attributes.
 */
function sk_genesis_attributes_entry_header( $attributes ) {

	$attributes['class'] = 'entry-header one-half first';

	return $attributes;

}

//* Add 'one-half' class to Entry Content to float it right
add_filter( 'genesis_attr_entry-content', 'sk_genesis_attributes_entry_content' );
/**
 * Add attributes for entry content element.
 *
 * @param array $attributes Existing attributes.
 *
 * @return array Amended attributes.
 */
function sk_genesis_attributes_entry_content( $attributes ) {

	$attributes['class'] = 'entry-content one-half';

	return $attributes;

}

//* Display values of custom fields (those that are not empty)
add_action( 'genesis_entry_header', 'sk_display_custom_fields' );

//* Remove default post image
remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );

//* Add post image in Entry Content above Excerpt
add_action( 'genesis_entry_content', 'sk_display_featured_image', 9 );
function sk_display_featured_image() {

	$image_args = array(
		'size' => 'medium',
		'attr' => array(
			'class' => 'alignright',
		),
	);

	$image = genesis_get_image( $image_args );

	if ( $image ) {
		echo '<a href="' . get_permalink() . '">' . $image .'</a>';
	}

}

//* To remove empty markup, '<p class="entry-meta"></p>' for entries that have not been assigned to any Genre
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
add_action( 'genesis_entry_footer', 'sk_custom_post_meta' );

genesis();