<?php
declare( strict_types=1 );

namespace Rarst\wps\Provider;

class WpProvider implements Provider {

	public function get_name(): string {
		return '$wp';
	}

	public function get_data(): array {
		global $wp;

		if ( ! $wp instanceof \WP ) {
			return [];
		}

		$output = get_object_vars( $wp );
		unset( $output['private_query_vars'], $output['public_query_vars'] );

		return array_filter( $output );
	}
}