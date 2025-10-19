<?php

if ( ! class_exists( 'Wp_Agent_Post_Taxonomy' ) ) {

	class Wp_Agent_Post_Taxonomy extends \Wp_Agents\Agents\Abstract_Llm_Agent {

		protected array $actions = [
			'post_updated'
		];

		protected array $tools = [
			Category_Lookup_Tool::class,
			Tag_Lookup_Tool::class
		];

		public function instructions(): string {
			return 'You are a WordPress editorial AI assistant. '
			       . 'Analyze the post content and assign the most relevant category and tags. '
			       . 'If the category and tags are already relevant enough, do not add extra. '
			       . 'Try using existing categories and tags, only create new if needed.'
			       . 'You can call tools to create them if missing. '
			       . 'Return only JSON in this exact format: '
			       . '{"category": "Travel", "tags": ["Thailand", "Beaches", "Vacation"]}.';
		}

		public function handle_response( mixed $answer, array $args = array() ): void {
			$post_id = (int) $args[0];
			$data    = json_decode( $answer, true );

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
	}

}
