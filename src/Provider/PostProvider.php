<?php
declare( strict_types=1 );

namespace Rarst\wps\Provider;

class PostProvider implements Provider {

	public function get_name(): string {
		return '$post';
	}

	public function get_data(): array {
		$post = get_post();

		if ( ! $post instanceof \WP_Post ) {
			return [];
		}

		return get_object_vars( $post );
	}
}