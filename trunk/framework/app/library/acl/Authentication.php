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
    
    public static $layoutAllows = array();
    public static $aclXml;
    
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
        $auth = self::getAclUtitlity()->getAuthentications();
        $action = self::SIGN_IN;
        if (isset($auth[$module][AclXmlConstant::ACL_SIGN_IN_ACTION])) {
            $action = $auth[$module][AclXmlConstant::ACL_SIGN_IN_ACTION];
        }
        return $action;
    }

    public static function getLoginController($module) {
        $auth = self::getAclUtitlity()->getAuthentications();
        if (is_null($auth[$module]["controller"])) {
            $message =  "Login Controller in Acl XML 'authentication' is not defined properly";
            throw new AiryException($message);
            return NULL;
        }
        return $auth[$module]["controller"];
    }

    public static function getLoginAction($module) {
        $auth = self::getAclUtitlity()->getAuthentications();
        $action = self::LOGIN;
        if (isset($auth[$module][AclXmlConstant::ACL_LOGIN_ERROR_ACTION])) {
            $action = $auth[$module][AclXmlConstant::ACL_LOGIN_ACTION];
        }
        return $action;
    }

    public static function getLoginErrorAction($module) {
        $auth = self::getAclUtitlity()->getAuthentications();
        $action = self::LOGIN_ERROR;
        if (isset($auth[$module][AclXmlConstant::ACL_LOGIN_ERROR_ACTION])) {
            $action = $auth[$module][AclXmlConstant::ACL_LOGIN_ERROR_ACTION];
        }
        return $action;
    }
    
    public static function getSuccessController($module) {
    	$auth = self::getAclUtitlity()->getSuccessfulDispatch();
    	var_dump($auth);
    	$successController = NULL;
    	if (isset($auth[$module]["controller"])) {
    		$successController = $auth[$module]["controller"];
    	}
    	return $successController;
    }
    
    public static function getSuccessAction($module) {
    	$auth = self::getAclUtitlity()->getSuccessfulDispatch();
    	$successAction = NULL;
    	if (isset($auth[$module]["action"])) {
    		$successAction = $auth[$module]["action"];
    	}
    	return $successAction;
    }
    
    public static function getOtherExclusiveActions($module) {
        $auth = self::getAclUtitlity()->getAuthentications();
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
        $auth = self::getAclUtitlity()->getAuthentications();
        if (!isset($auth[$module])) {
            $message = "Module {$module} is not defined or is mismatched in Acl XML 'authentication' section when config setting use_authentication is enable";       	
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
        $rules = self::getAclUtitlity()->getBrowseRules();
        $allows = (isset($rules[$module]))? $rules[$module]: null;
        return $allows;
    }
    
    public static function addLayoutAllowAction($module, $controllerName, $actionName) {
    	self::$layoutAllows[$module][$controllerName][] = $actionName;
    }
    
    public static function removeLayoutAllowAction($module, $controllerName, $actionName) {
    	foreach (self::$layoutAllows[$module][$controllerName] as $idx => $allowActionName) {
    		if ($actionName == $allowActionName) {
    			unset(self::$layoutAllows[$module][$controllerName][$idx]);
    		}
    	}
    	if (count(self::$layoutAllows[$module][$controllerName]) == 0) {
    		unset(self::$layoutAllows[$module][$controllerName]);
    	}
    	if (count(self::$layoutAllows[$module]) == 0) {
    		unset(self::$layoutAllows[$module]);
    	}
    }
    
    private static function getAclUtitlity() {
        $acl = AclUtility::getInstance();
    	if (!is_null(self::$aclXml)) {
    		$acl->setAclXml(self::$aclXml);
    	}
    	return $acl;
    }
    
    public static function setAclXml($xml) {
    	self::$aclXml = $xml;
    }
    

}

?>