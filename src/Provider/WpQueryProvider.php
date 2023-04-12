<?php
declare( strict_types=1 );

namespace Rarst\wps\Provider;

class WpQueryProvider implements Provider {

	public function get_name(): string {
		return '$wp_query';
	}

	public function get_data(): array {
		global $wp_query;

		if ( ! $wp_query instanceof \WP_Query ) {
			return [];
		}

		$output               = get_object_vars( $wp_query );
		$output['query_vars'] = array_filter( $output['query_vars'] );
		unset( $output['posts'], $output['post'] );

		return array_filter( $output );
	}
}