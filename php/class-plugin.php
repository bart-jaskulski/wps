<?php declare(strict_types=1);

namespace Rarst\wps;

use Rarst\wps\Vendor\Pimple\Container;
use Rarst\wps\Vendor\Whoops\Handler\PlainTextHandler;
use Rarst\wps\Vendor\Whoops\Handler\PrettyPageHandler;
use Rarst\wps\Vendor\Whoops\Run;
use Rarst\wps\Vendor\Whoops\Util\Misc;

/**
 * Main plugin's class.
 */
class Plugin extends Container {

	/**
	 * @param array $values Optional arguments for container.
	 */
	public function __construct( array $values = [] ) {

		$defaults = [];

		$defaults['tables'] = [
			'$wp'       => function () {
				global $wp;

				if ( ! $wp instanceof \WP ) {
					return [];
				}

				$output = get_object_vars( $wp );
				unset( $output['private_query_vars'], $output['public_query_vars'] );

				return array_filter( $output );
			},
			'$wp_query' => function () {
				global $wp_query;

				if ( ! $wp_query instanceof \WP_Query ) {
					return [];
				}

				$output               = get_object_vars( $wp_query );
				$output['query_vars'] = array_filter( $output['query_vars'] );
				unset( $output['posts'], $output['post'] );

				return array_filter( $output );
			},
			'$post'     => function () {
				$post = get_post();

				if ( ! $post instanceof \WP_Post ) {
					return [];
				}

				return get_object_vars( $post );
			},
		];

		$defaults['handler.pretty'] = static function ( $plugin ) {
			$handler = new PrettyPageHandler();

			foreach ( $plugin['tables'] as $name => $callback ) {
				$handler->addDataTableCallback( $name, $callback );
			}

			// Requires Remote Call plugin.
			$handler->addEditor( 'phpstorm-remote-call', 'http://localhost:8091?message=%file:%line' );

			return $handler;
		};

		$defaults['handler.json'] = static function () {
			$handler = new Admin_Ajax_Handler();
			$handler->addTraceToOutput( true );

			return $handler;
		};

		$defaults['handler.rest'] = static function () {
			$handler = new Rest_Api_Handler();
			$handler->addTraceToOutput( true );

			return $handler;
		};

		$defaults['handler.text'] = static function () {
			return new PlainTextHandler();
		};

		$defaults['run'] = static function ( $plugin ) {
			$run = new Run();
			$run->pushHandler( $plugin['handler.pretty'] );
			$run->pushHandler( $plugin['handler.json'] );
			$run->pushHandler( $plugin['handler.rest'] );

			if ( Misc::isCommandLine() ) {
				$run->pushHandler( $plugin['handler.text'] );
			}

			return $run;
		};

		parent::__construct( array_merge( $defaults, $values ) );
	}

	public function is_debug(): bool {
		return defined( 'WP_DEBUG' ) && WP_DEBUG;
	}

	public function is_debug_display(): bool {
		return defined( 'WP_DEBUG_DISPLAY' ) && false !== WP_DEBUG_DISPLAY;
	}

	/**
	 * Execute run conditionally on debug configuration.
	 */
	public function run(): void {
		if ( ! $this->is_debug() || ! $this->is_debug_display() ) {
			return;
		}

		/** @var Run $run */
		$run = $this['run'];
		$run->register();
		ob_start(); // Or we are going to be spitting out WP markup before whoops.
	}
}
