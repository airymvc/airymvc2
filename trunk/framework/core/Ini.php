<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * This contains the autoload function to auto loading the object.
 *
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
set_include_path(get_include_path() . PATH_SEPARATOR . "core");

/**
 * The auto load function
 * 
 * @param object $object the class that will be auto load
 */
function __autoload($object)
{  
	require_once("{$object}.php");
}
