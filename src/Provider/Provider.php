<?php
declare( strict_types=1 );

namespace Rarst\wps\Provider;

interface Provider {

	public function get_name(): string;

	public function get_data(): array;

}