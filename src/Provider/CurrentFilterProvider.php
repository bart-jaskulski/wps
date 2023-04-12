<?php
declare( strict_types=1 );

namespace Rarst\wps\Provider;

class CurrentFilterProvider implements Provider {

	public function get_name(): string {
		return '$wp_current_filter';
	}

	public function get_data(): array {
		return (array) $GLOBALS['wp_current_filter'];
	}
}