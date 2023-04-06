<?php
namespace Rarst\wps;

use Rarst\wps\Vendor\Whoops\Exception\Formatter;
use Rarst\wps\Vendor\Whoops\Handler\Handler;
use Rarst\wps\Vendor\Whoops\Handler\JsonResponseHandler;
use Rarst\wps\Vendor\Whoops\Util\Misc;

/**
 * WordPress-specific version of Json handler.
 */
class Admin_Ajax_Handler extends JsonResponseHandler {

	/**
	 * @return bool
	 */
	private function isAjaxRequest() {

		return defined( 'DOING_AJAX' ) && DOING_AJAX;
	}

	/**
	 * @return int
	 */
	public function handle() {

		if ( ! $this->isAjaxRequest() ) {
			return Handler::DONE;
		}

		$response = array(
			'success' => false,
			'data'    => Formatter::formatExceptionAsDataArray( $this->getInspector(), $this->addTraceToOutput() ),
		);

		if ( Misc::canSendHeaders() ) {
			header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
		}

		echo wp_json_encode( $response, JSON_PRETTY_PRINT );

		return Handler::QUIT;
	}
}
