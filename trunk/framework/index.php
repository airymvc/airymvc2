<?php
/**
 * AiryMVC Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license.
 *
 * It is also available at this URL: http://opensource.org/licenses/BSD-3-Clause
 * The project website URL: https://code.google.com/p/airymvc/
 *
 *
 */


	include("core".DIRECTORY_SEPARATOR."Ini.php");
	Initializer::initialize();
	$Router = Loader::load("Router");
	Dispatcher::dispatch($Router);
?>