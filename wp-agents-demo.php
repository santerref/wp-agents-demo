<?php
/*
 * Plugin Name: WP Agent Demo
 * Version: 0.0.1
 * Requires Plugins: wp-agents
 */

add_action( 'wp_agents_register_agents', function () {
	require_once __DIR__ . '/tools.php';
	require_once __DIR__ . '/agents.php';

	wp_agents_register(
		'taxonomy_agent',
		Wp_Agent_Post_Taxonomy::class,
	);
} );

add_action( 'post_updated', function ( $post_id, $post_after ) {
	if ( 'post' === $post_after->post_type ) {
		$categories = wp_get_post_categories( $post_after->ID, array( 'fields' => 'names' ) );
		$tags       = wp_get_post_tags( $post_after->ID, array( 'fields' => 'names' ) );

		$category_list = empty( $categories ) ? 'none' : implode( ', ', $categories );
		$tag_list      = empty( $tags ) ? 'none' : implode( ', ', $tags );

		$input = "Post title: {$post_after->post_title}\n\n"
		         . "Post content: {$post_after->post_content}\n\n"
		         . "Existing category: {$category_list}\n"
		         . "Existing tags: {$tag_list}";

		$response = wp_agents_get( 'taxonomy_agent' )
			->prompt( $input )
			->chat();

		$data = json_decode( $response->get_content(), true );

		if ( ! empty( $data['category'] ) ) {
			$term = get_term_by( 'name', $data['category'], 'category' );
			if ( $term ) {
				wp_set_post_terms( $post_id, [ $term->term_id ], 'category' );
			}
		}

		if ( ! empty( $data['tags'] ) && is_array( $data['tags'] ) ) {
			$tag_ids = [];
			foreach ( $data['tags'] as $tag_name ) {
				$term = get_term_by( 'name', $tag_name, 'post_tag' );
				if ( $term ) {
					$tag_ids[] = $term->term_id;
				}
			}
			if ( $tag_ids ) {
				wp_set_post_terms( $post_id, $tag_ids );
			}
		}
	}
}, 10, 2 );
