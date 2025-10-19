<?php

if ( ! class_exists( 'Category_Lookup_Tool' ) ) {

	class Category_Lookup_Tool implements \Wp_Agents\Tools\Tool_Interface {

		public function definition(): array {
			return array(
				'name'        => 'find_or_create_category',
				'description' => 'Creates a WordPress category if missing.',
				'parameters'  => [
					'type'       => 'object',
					'properties' => [
						'name' => [ 'type' => 'string' ],
					],
					'required'   => [ 'name' ],
				],
			);
		}

		public function execute( array $arguments ): mixed {
			$term = get_term_by( 'name', $arguments['name'], 'category', ARRAY_A );
			if ( ! $term ) {
				$term = wp_insert_term( $arguments['name'], 'category' );
			}

			return [ 'term_id' => (int) ( $term['term_id'] ?? 0 ) ];
		}
	}

}

if ( ! class_exists( 'Tag_Lookup_Tool' ) ) {

	class Tag_Lookup_Tool implements \Wp_Agents\Tools\Tool_Interface {

		public function definition(): array {
			return array(
				'name'        => 'find_or_create_tags',
				'description' => 'Creates WordPress tags if missing.',
				'parameters'  => [
					'type'       => 'object',
					'properties' => [
						'names' => [
							'type'  => 'array',
							'items' => [ 'type' => 'string' ],
						],
					],
					'required'   => [ 'names' ],
				],
			);
		}

		public function execute( array $arguments ): array {
			$ids = [];

			foreach ( $arguments['names'] as $name ) {
				$term = get_term_by( 'name', $name, 'post_tag', ARRAY_A );
				if ( ! $term ) {
					$term = wp_insert_term( $name, 'post_tag' );
				}

				if ( is_array( $term ) && isset( $term['term_id'] ) ) {
					$ids[] = (int) $term['term_id'];
				} elseif ( $term instanceof \WP_Term ) {
					$ids[] = (int) $term->term_id;
				}
			}

			return [ 'term_ids' => $ids ];
		}
	}

}
