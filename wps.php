<?php
/*
Plugin Name: wps
Plugin URI: https://github.com/bart-jaskulski/wps
Description: WordPress plugin for Whoops error handler.
Author: Andrey "Rarst" Savchenko, Bartek Jaskulski
Version: 2.0.0
Author URI: http://www.rarst.net/
License: MIT
Requires at least: 5.9

Copyright (c) 2013 Andrey "Rarst" Savchenko
Copyright (c) 2023 Bartek Jaskulski

Permission is hereby granted, free of charge, to any person obtaining a copy of this
software and associated documentation files (the "Software"), to deal in the Software
without restriction, including without limitation the rights to use, copy, modify, merge,
publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons
to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies
or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
DEALINGS IN THE SOFTWARE.
*/

// Bail early on a series of preliminary checks.
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
	return;
}

if ( ! defined( 'WP_DEBUG_DISPLAY' ) || ! WP_DEBUG_DISPLAY ) {
	return;
}

// Special GET parameter to disable wps.
if ( isset( $_GET['wps_disable'] ) ) {
	return;
}

require __DIR__ . '/vendor/autoload.php';

$container = new \Rarst\wps\Vendor\Pimple\Container();
$container->register( new \Rarst\wps\ServiceProvider() );
$wps = new \Rarst\wps\Plugin( $container[ \Rarst\wps\Vendor\Whoops\Run::class ] );
do_action( 'wps/loaded', $wps );
$wps->run();
