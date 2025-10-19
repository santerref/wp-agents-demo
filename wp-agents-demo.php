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
