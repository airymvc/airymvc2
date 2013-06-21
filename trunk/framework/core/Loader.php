<?php
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
