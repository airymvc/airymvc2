<?php
	include("core".DIRECTORY_SEPARATOR."Ini.php");
	Initializer::initialize();
	$Router = Loader::load("Router");
	Dispatcher::dispatch($Router);
?>