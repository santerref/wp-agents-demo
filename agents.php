<?php

if ( ! class_exists( 'Wp_Agent_Post_Taxonomy' ) ) {

	class Wp_Agent_Post_Taxonomy extends \Wp_Agents\Agents\Abstract_Llm_Agent {

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

	}

}
