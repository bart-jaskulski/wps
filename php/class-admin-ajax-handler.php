<?php declare(strict_types=1);

namespace Rarst\wps;

use Rarst\wps\Vendor\Whoops\Exception\Formatter;
use Rarst\wps\Vendor\Whoops\Handler\Handler;
use Rarst\wps\Vendor\Whoops\Handler\JsonResponseHandler;
use Rarst\wps\Vendor\Whoops\Util\Misc;

/**
 * WordPress-specific version of Json handler.
 */
class Admin_Ajax_Handler extends JsonResponseHandler {

	private function isAjaxRequest(): bool {
		return defined( 'DOING_AJAX' ) && DOING_AJAX;
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
