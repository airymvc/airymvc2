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
 * Description of LoginSessionService
 *
 * @author Hung-Fu Aaron Chang
 */
class LoginService {
    
    private static $instance; 
    /**
     *  Use Singleton pattern here
     */
    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }    
        
        return self::$instance;
    }    
    /**
     *
     * @param String $moduleName
     * @return String uid 
     */
    public function getLoginUserId($moduleName = null)
    {
        if (!is_null($moduleName)) {
            return $_SESSION[$moduleName][Authentication::UID];
        }
        
        $currentModuleName = MvcReg::getModuleName();
        return $_SESSION[$currentModuleName][Authentication::UID];
        
    }
    public function getEncryptLoginUserId ($moduleName = null){
        if (!is_null($moduleName)) {
            return $_SESSION[$moduleName][Authentication::ENCRYPT_UID];
        }
        
        $currentModuleName = MvcReg::getModuleName();
        return $_SESSION[$currentModuleName][Authentication::ENCRYPT_UID];        
    }
    public function isLogin($moduleName = null)
    {
        if (!is_null($moduleName)) {
            return $_SESSION[$moduleName][Authentication::UID];
        }
        
        $currentModuleName = MvcReg::getModuleName();
        return $_SESSION[$currentModuleName][Authentication::UID];        
    }
    /**
     * setLogin : Save the session data with uid, moduleName
     * @params: String $uid, String $moduleName
     */
    public function setLogin($moduleName, $uid) {

            $_SESSION[$moduleName][Authentication::UID] = $uid;
            $_SESSION[$moduleName][Authentication::ENCRYPT_UID] = Base64UrlCode::encrypt($uid);
            $_SESSION[$moduleName][Authentication::IS_LOGIN] = true;
            $_SESSION[Authentication::UID]['module'] = $moduleName;

    }
    
    
}

?>
