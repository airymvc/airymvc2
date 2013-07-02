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

class Loader
{
  private static $loaded = array();
  
  public static function load($object) {
    $valid = array(  "Ini",
                     "Initializer",
                     "MvcReg",
                     "PathService",
                     "Router",
                     "Dispatcher",
                     "AppError",
    			  );
    			  
    if (!in_array($object, $valid)){
       throw new Exception("Not a valid object '{$object}' to load");
    }
    if (empty(self::$loaded[$object])){
      self::$loaded[$object]= new $object();
    }
    return self::$loaded[$object];
  }
}
?>
