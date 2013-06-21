<?php

/**
 * This class deals with the view file storage 
 */

class Storage {
    
    /**
     * This is used for save the varialbe into an array
     * @var array 
     */
    static public $_varArray = array();
    
    /**
     * setter
     * @param array $varArray 
     */
    public static function setVarArray($varArray) {
        
        self::$_varArray = $varArray;
    }
    
    /**
     * getter
     * @return array 
     */
    public static function getVarArray() {
        
        return self::$_varArray;
    }
    
    /**
     * setter
     * 
     * @param string $key
     * @param string $value 
     */
    public static function setVar($key, $value) {
        
        self::$_varArray[$key] = $value;
    }
    
    /**
     * getter
     * 
     * @param string $key
     * @return string 
     */
    public static function getVar($key) {
        
        return self::$_varArray[$key];
    }
}
?>
