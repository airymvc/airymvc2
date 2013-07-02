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
 * @author Hung-Fu Aaron Chang
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
