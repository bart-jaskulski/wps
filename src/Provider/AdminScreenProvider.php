<?php
declare( strict_types=1 );

namespace Rarst\wps\Provider;

class AdminScreenProvider implements Provider {

	public function get_name(): string {
		return '$current_screen';
	}

	public function get_data(): array {
		if ( ! $this->is_admin_context() ) {
			return [];
		}

		return get_current_screen() ? get_object_vars( get_current_screen() ) : [];
	}

	public function is_admin_context(): bool {
		return function_exists( 'get_current_screen' );
	}
}