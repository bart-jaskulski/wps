<?php
declare( strict_types=1 );

namespace Rarst\wps;

use Rarst\wps\Handler\AdminAjaxHandler;
use Rarst\wps\Handler\RestApiHandler;
use Rarst\wps\Provider\Provider;
use Rarst\wps\Vendor\Pimple\Container;
use Rarst\wps\Vendor\Whoops\Handler\PlainTextHandler;
use Rarst\wps\Vendor\Whoops\Handler\PrettyPageHandler;
use Rarst\wps\Vendor\Whoops\Run;
use Rarst\wps\Vendor\Whoops\Util\Misc;

class ServiceProvider implements Vendor\Pimple\ServiceProviderInterface {

	public function register( Container $c ): void {
		$c[ AdminAjaxHandler::class ] = static function () {
			$handler = new AdminAjaxHandler();
			$handler->addTraceToOutput( true );

			return $handler;
		};

		$c[ RestApiHandler::class ] = static function () {
			$handler = new RestApiHandler();
			$handler->addTraceToOutput( true );

			return $handler;
		};

		$c[ PrettyPageHandler::class ] = static function () {
			$handler   = new PrettyPageHandler();
			$providers = [
				new \Rarst\wps\Provider\CurrentFilterProvider(),
				new \Rarst\wps\Provider\PostProvider(),
				new \Rarst\wps\Provider\WpQueryProvider(),
				new \Rarst\wps\Provider\WpProvider(),
				new \Rarst\wps\Provider\AdminScreenProvider(),
			];

			// By default, we treat everything from WP_CONTENT_DIR as application code, this
			// behavior should be flexible, so we are going to make it configurable.
			$handler->setApplicationPaths( [ \WP_CONTENT_DIR ] );

			// We are adding our own resources as a workaround, until following lands: https://github.com/filp/whoops/pull/756
			$handler->addResourcePath( __DIR__ . '/Resources' );

			/** @var Provider $provider */
			foreach ( $providers as $provider ) {
				$handler->addDataTableCallback( $provider->get_name(), [ $provider, 'get_data' ] );
			}

			// Requires Remote Call plugin.
//			$handler->setEditor( 'phpstorm' );
			$handler->addEditor( 'phpstorm-remote-call', 'http://localhost:8091?message=%file:%line' );

			return $handler;
		};

		$c[ Run::class ] = static function ( Container $c ) {
			$whoops = new Run();
			// TODO: register only if we are in required context. Handler don't have to know if
			// it's currently able to handle the exception.
			$whoops->pushHandler( $c[ PrettyPageHandler::class ] );
			$whoops->pushHandler( $c[ AdminAjaxHandler::class ] );
			$whoops->pushHandler( $c[ RestApiHandler::class ] );

			if ( Misc::isCommandLine() ) {
				$whoops->pushHandler( new PlainTextHandler() );
			}

			return $whoops;
		};
	}
}
