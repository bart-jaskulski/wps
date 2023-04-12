<?php
declare( strict_types=1 );

namespace Rarst\wps;

use Rarst\wps\Vendor\Whoops\Run;

final class Plugin {

	/** @var Run */
	private $whoops;

	public function __construct( Run $whoops ) {
		$this->whoops = $whoops;
	}

	/**
	 * Silence particular errors in particular files.
	 *
	 * @param array|string $patterns List or a single regex pattern to match.
	 * @param int $levels Defaults to E_STRICT | E_DEPRECATED.
	 */
	public function silence_errors( $patterns, int $levels ): void {
		$this->whoops->silenceErrorsInPaths( $patterns, $levels );
	}

	public function run(): void {
		/**
		 * It would be nice, if we used Run::handleException, as this would allow us to control,
		 * where we are going to output the error. Without this, we miss Whoops functionality to
		 * display an error without preventing the rest of the page from loading, which may be
		 * desirable in E_NOTICE or similar cases.
		 */
//		$this->whoops->allowQuit( false );
//		$this->whoops->writeToOutput( false );
		$this->whoops->register();
		ob_start(); // Or we are going to be spitting out WP markup before whoops.
	}

}
