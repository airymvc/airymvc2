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

<?php

class Parameter {
    /**
     * This is used for save the varialbe into an array
     * @var array 
     */
    public static $_params = array();
    
    /**
     * setter
     * @param array $params 
     */
    public static function setParams($params) {
        
        self::$_params = $params;
    }
    
    /**
     * getter
     * @return array 
     */
    public static function getParams() {
        
        return self::$_params;
    }
    
    /**
     * setter
     * 
     * @param string $key
     * @param string $value 
     */
    public static function setParam($key, $value) {
        
        self::$_params[$key] = $value;
    }
    
    /**
     * getter
     * 
     * @param string $key
     * @return string 
     */
    public static function getParam($key) {
        
        return self::$_params[$key];
    }
    
    /**
     * set params to Session
     */
    public static function setSession($params) {
    	foreach ($params as $key => $value) {
    		$_SESSION[$key] = $value;
    	}
    }

    /**
     * unset params to Session
     */
    public static function unsetSession($params) {
    	foreach ($params as $key => $value) {
    		unset($_SESSION[$key]);
    	}
    }    
    
    /**
     * get parameter from Session
     */
    public static function getSession($key) {
    	return $_SESSION[$key];
    } 
    
    /**
     * get parameter from Session
     */
    public static function getModuleSession($key) {
    	$moduleName = MvcReg::getModuleName();
    	return $_SESSION[$moduleName][$key];
    }
    
    /**
     * set params to Session
     */
    public static function setModuleSession($params) {
    	$moduleName = MvcReg::getModuleName();
    	foreach ($params as $key => $value) {
    		$_SESSION[$moduleName][$key] = $value;
    	}
    }
    
    /**
     * set params to Session
     */
    public static function unsetModuleSession($params) {
    	$moduleName = MvcReg::getModuleName();
    	foreach ($params as $key => $value) {
    		unset($_SESSION[$moduleName][$key]);
    	}
    }

    /**
     * set params to Session
     */
    public static function unsetAllParams() {
    	foreach ($this->_params as $key => $value) {
    		unset($this->_params[$key]);
    	}
    }
}