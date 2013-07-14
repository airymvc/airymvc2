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


class Authentication {

    const IS_LOGIN = "islogin";
    const UID = "uid";
    const ENCRYPT_UID = "encrypt_uid";
    
    
    //Four default login related actions
    const SIGN_IN = "signIn";
    const LOGIN = "login";
    const LOGIN_ERROR = "loginError";
    const LOGOUT = "logout";
    
    public static function isLogin($moduleName) {
        /**
         * Use uid and module for now  
         */
        if (empty($_SESSION) || empty($_SESSION[$moduleName][self::IS_LOGIN]) || $_SESSION[$moduleName][self::IS_LOGIN] == false) {
            return false;
        }
        return true;
    }

    public static function getSignInAction($module) {
        $auth = AclUtility::getInstance()->getAuthentications();
        $action = self::SIGN_IN;
        if (isset($auth[$module][AclXmlConstant::ACL_SIGN_IN_ACTION])) {
            $action = $auth[$module][AclXmlConstant::ACL_SIGN_IN_ACTION];
        }
        return $action;
    }

    public static function getLoginController($module) {
        $auth = AclUtility::getInstance()->getAuthentications();
        if (is_null($auth[$module]["controller"])) {
            $message =  "Login Controller in Acl XML 'authentication' is not defined properly";
            throw new AiryException($message);
            return NULL;
        }
        return $auth[$module]["controller"];
    }

    public static function getLoginAction($module) {
        $auth = AclUtility::getInstance()->getAuthentications();
        $action = self::LOGIN;
        if (isset($auth[$module][AclXmlConstant::ACL_LOGIN_ERROR_ACTION])) {
            $action = $auth[$module][AclXmlConstant::ACL_LOGIN_ACTION];
        }
        return $action;
    }

    public static function getLoginErrorAction($module) {
        $auth = AclUtility::getInstance()->getAuthentications();
        $action = self::LOGIN_ERROR;
        if (isset($auth[$module][AclXmlConstant::ACL_LOGIN_ERROR_ACTION])) {
            $action = $auth[$module][AclXmlConstant::ACL_LOGIN_ERROR_ACTION];
        }
        return $action;
    }

    public static function getOtherExclusiveActions($module) {
        $auth = AclUtility::getInstance()->getAuthentications();
        $actions = array();
        if (isset($auth[$module][AclXmlConstant::ACL_OTHER_EXCLUSIVE_ACTIONS])) {
        	foreach ($auth[$module][AclXmlConstant::ACL_OTHER_EXCLUSIVE_ACTIONS] as $idx => $exAction) {
           			 $actions[$idx] = $auth[$module][AclXmlConstant::ACL_OTHER_EXCLUSIVE_ACTIONS][$idx];
        	}
        }
	    return $actions;
    }

    public static function getLoginExcludeActions($module) {
        $loginActions = array();
        $auth = AclUtility::getInstance()->getAuthentications();
        if (!isset($auth[$module])) {
            $message = "Module {$module} is not defined or mismatched in Acl XML 'authentication' section when use_authentication is enable";       	
        	throw new AiryException($message);
            return NULL;
        }
        
        if (is_null($auth[$module]["controller"])) {
        	$message = "Acl XML is not defined properly, check your authentication settings for module {$module}";
        	throw new AiryException($message);
            return NULL;
        }
        if (!isset($auth[$module][AclXmlConstant::ACL_SIGN_IN_ACTION])) {
            $loginActions[$auth[$module]["controller"]][self::SIGN_IN] = self::SIGN_IN;
        } else {
            $loginActions[$auth[$module]["controller"]][$auth[$module][AclXmlConstant::ACL_SIGN_IN_ACTION]] = $auth[$module][AclXmlConstant::ACL_SIGN_IN_ACTION];
        }

        if (!isset($auth[$module][AclXmlConstant::ACL_LOGIN_ACTION])) {
            $loginActions[$auth[$module]["controller"]][self::LOGIN] = self::LOGIN;
        } else {
            $loginActions[$auth[$module]["controller"]][$auth[$module][AclXmlConstant::ACL_LOGIN_ACTION]] = $auth[$module][AclXmlConstant::ACL_LOGIN_ACTION];
        }

        if (!isset($auth[$module][AclXmlConstant::ACL_LOGIN_ERROR_ACTION])) {
            $loginActions[$auth[$module]["controller"]][self::LOGIN_ERROR] = self::LOGIN_ERROR;
        } else {
            $loginActions[$auth[$module]["controller"]][$auth[$module][AclXmlConstant::ACL_LOGIN_ERROR_ACTION]] = $auth[$module][AclXmlConstant::ACL_LOGIN_ERROR_ACTION];
        }
        if (!isset($auth[$module][AclXmlConstant::ACL_LOGOUT_ACTION])) {
            $loginActions[$auth[$module]["controller"]][self::LOGOUT] = self::LOGOUT;
        } else {
            $loginActions[$auth[$module]["controller"]][$auth[$module][AclXmlConstant::ACL_LOGOUT_ACTION]] = $auth[$module][AclXmlConstant::ACL_LOGOUT_ACTION];
        }
        
        //Here we deal with other exclusive actions
        if (isset($auth[$module][AclXmlConstant::ACL_OTHER_EXCLUSIVE_ACTIONS])) {
        	foreach ($auth[$module][AclXmlConstant::ACL_OTHER_EXCLUSIVE_ACTIONS] as $idx => $exAction) {
           			 $loginActions[$auth[$module]["controller"]][$auth[$module][AclXmlConstant::ACL_OTHER_EXCLUSIVE_ACTIONS][$idx]] = $auth[$module][AclXmlConstant::ACL_OTHER_EXCLUSIVE_ACTIONS][$idx];
        	}
        }

        return $loginActions;
    }

    public static function getAllAllows($module) {
        $auth = AclUtility::getInstance();
        $rules = $auth->getBrowseRules();
        $allows = (isset($rules[$module]))? $rules[$module]: null;
        return $allows;
    }

}

?>