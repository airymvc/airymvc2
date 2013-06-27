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
 */


class Authentication {

    const IS_LOGIN = "islogin";
    const UID = "uid";
    const ENCRYPT_UID = "encrypt_uid";

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
        $action = DefaultLoginAction::SIGN_IN;
        if (isset($auth[$module]["sign_in_action"])) {
            $action = $auth[$module]["sign_in_action"];
        }
        return $action;
    }

    public static function getLoginController($module) {
        $auth = AclUtility::getInstance()->getAuthentications();
        if (is_null($auth[$module]["controller"])) {
            echo "Acl XML is not defined properly, check your authentication settings";
            return null;
        }
        return $auth[$module]["controller"];
    }

    public static function getLoginAction($module) {
        $auth = AclUtility::getInstance()->getAuthentications();
        $action = DefaultLoginAction::LOGIN;
        ;
        if (isset($auth[$module]["login_action"])) {
            $action = $auth[$module]["login_action"];
        }
        return $action;
    }

    public static function getLoginErrorAction($module) {
        $auth = AclUtility::getInstance()->getAuthentications();
        $action = DefaultLoginAction::LOGIN_ERROR;
        if (isset($auth[$module]["login_error_action"])) {
            $action = $auth[$module]["login_error_action"];
        }
        return $action;
    }

    public static function getRegisterAction($module) {
        $auth = AclUtility::getInstance()->getAuthentications();
        $action = DefaultLoginAction::REGISTER;
        if (isset($auth[$module]["register_action"])) {
            $action = $auth[$module]["register_action"];
        }
        return $action;
    }

    public static function getLoginExcludeActions($module) {
        $loginActions = array();
        $auth = AclUtility::getInstance()->getAuthentications();
        if (is_null($auth[$module]["controller"])) {
            echo "Acl XML is not defined properly, check your authentication settings";
            return null;
        }
        if (!isset($auth[$module]["sign_in_action"])) {
            $loginActions[$auth[$module]["controller"]][DefaultLoginAction::SIGN_IN] = DefaultLoginAction::SIGN_IN;
        } else {
            $loginActions[$auth[$module]["controller"]][$auth[$module]["sign_in_action"]] = $auth[$module]["sign_in_action"];
        }

        if (!isset($auth[$module]["login_action"])) {
            $loginActions[$auth[$module]["controller"]][DefaultLoginAction::LOGIN] = DefaultLoginAction::LOGIN;
        } else {
            $loginActions[$auth[$module]["controller"]][$auth[$module]["login_action"]] = $auth[$module]["login_action"];
        }

        if (!isset($auth[$module]["login_error_action"])) {
            $loginActions[$auth[$module]["controller"]][DefaultLoginAction::LOGIN_ERROR] = DefaultLoginAction::LOGIN_ERROR;
        } else {
            $loginActions[$auth[$module]["controller"]][$auth[$module]["login_error_action"]] = $auth[$module]["login_error_action"];
        }

        if (!isset($auth[$module]["register_action"])) {
            $loginActions[$auth[$module]["controller"]][DefaultLoginAction::REGISTER] = DefaultLoginAction::REGISTER;
        } else {
            $loginActions[$auth[$module]["controller"]][$auth[$module]["register_action"]] = $auth[$module]["register_action"];
        }
        if (!isset($auth[$module]["loginout"])) {
            $loginActions[$auth[$module]["controller"]][DefaultLoginAction::LOGOUT] = DefaultLoginAction::LOGOUT;
        } else {
            $loginActions[$auth[$module]["controller"]][$auth[$module]["loginout"]] = $auth[$module]["loginout"];
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