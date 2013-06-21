<?php
/**
 * Description of Ini
 *
 * @author Hung-Fu Aaron Chang
 */
set_include_path(get_include_path() . PATH_SEPARATOR . "core");
	//set_include_path - Sets the include_path configuration option
function __autoload($object)
{  
	require_once("{$object}.php");
}
