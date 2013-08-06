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
 * @author: Hung-Fu Aaron Chang
 */

/**
 * Description of Ini
 *
 * @author Hung-Fu Aaron Chang
 */
set_include_path(get_include_path() . PATH_SEPARATOR . "core");

function __autoload($object)
{  
	require_once("{$object}.php");
}
