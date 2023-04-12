<?php declare( strict_types=1 );

namespace Rarst\wps\Handler;

use Rarst\wps\Vendor\Whoops\Exception\Formatter;
use Rarst\wps\Vendor\Whoops\Handler\Handler;
use Rarst\wps\Vendor\Whoops\Handler\JsonResponseHandler;
use Rarst\wps\Vendor\Whoops\Util\Misc;

/**
 * WordPress-specific version of Json handler.
 */
class AdminAjaxHandler extends JsonResponseHandler {

	private function isAjaxRequest(): bool {
		return wp_doing_ajax();
	}

	public function handle(): int {
		if ( ! $this->isAjaxRequest() ) {
			return Handler::DONE;
		}

		$response = [
			'success' => false,
			'data'    => Formatter::formatExceptionAsDataArray( $this->getInspector(), $this->addTraceToOutput() ),
		];

		if ( Misc::canSendHeaders() ) {
			header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
		}

		echo wp_json_encode( $response, \JSON_PRETTY_PRINT );

		return Handler::QUIT;
	}
}
