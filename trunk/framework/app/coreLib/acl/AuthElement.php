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
 *
 */

require_once ('AcDb.php');
require_once ('AclUtility.php');

class AuthElement {
    
        /**
     * signIn, check with the database table with uid, pwd and mapping table
     * @params: String $uid, String $pwd, String $mapTbl
     */
    public function signIn() {

        $moduleName = MvcReg::getModuleName();
        $acl = AclUtility::getInstance();
        $tbl_id = $acl->getTableIdByModule($moduleName);
        $AcDb = new AcDb();

        $tableName = $acl->getTableById($tbl_id);
        $mapFields = $acl->getMappingFieldByTbl($tbl_id);

        $db_uid = $mapFields["user_id"];
        $db_pwd = $mapFields["pwd"];
        $db_salt = (isset($mapFields["pwd_encrypt"])) ? $mapFields["pwd_encrypt"] : null;
        $db_isdelete = (isset($mapFields["is_delete"])) ? $mapFields["is_delete"] : null;
        $db_isdelete_value = (is_null($db_isdelete)) ? 0 : null;

        $uid = $this->params["{$db_uid}"];
        $pwd = $this->params["{$db_pwd}"];

        $mysql_results = null;
        if (!is_null($db_salt)) {
            $salt = md5(trim($pwd));
            $mysql_results = $AcDb->getUserByUidPwd($tableName, $db_uid, $uid, $db_salt, $db_isdelete, $db_isdelete_value);
        } else {
            $mysql_results = $AcDb->getUserByUidPwd($tableName, $db_uid, $uid, $db_pwd, $db_isdelete, $db_isdelete_value);
        }
        $rows = mysql_fetch_array($mysql_results, MYSQL_ASSOC);
        $bLogin = false;
        
        if (!is_null($db_salt)) {
            if ($rows[$db_salt] == $salt) {
                $bLogin = true;
            }
        } else {
            if ($rows[$db_pwd] == $pwd) {
                $bLogin = true;
            }
        }

        if ($bLogin) {

            $_SESSION[$moduleName][Authentication::UID] = $uid;
            $_SESSION[$moduleName][Authentication::ENCRYPT_UID] = Base64UrlCode::encrypt($uid);
            $_SESSION[$moduleName][Authentication::IS_LOGIN] = true;
            $_SESSION[Authentication::UID]['module'] = $moduleName;
            foreach ($rows as $key => $value) {
                    $_SESSION[$moduleName]['user'][$key] = $value;
            }
            
            $successfulArray = $acl->getSuccessfulDispatch();
            $successfulController = $successfulArray[$moduleName]['controller'];
            $successfulAction = $successfulArray[$moduleName]['action'];

            Dispatcher::forward($moduleName, $successfulController, $successfulAction, $this->params);
        } else {
            $authArray = $acl->getAuthentications();
            if (isset($authArray[$moduleName]['login_error_action'])) {
                $loginErrorActionName = $authArray[$moduleName]['login_error_action'];
                $loginErrorAction = $loginErrorActionName . self::ACTION_POSTFIX;
            } else {
                $loginErrorAction = "loginErrorAction";
            }
            $this->$loginErrorAction();
        }
    }
    
    
    
    
    public function prepareLoginForm($uidLabel = null, $pwdLabel = null, $insertHtmlString = null) {

        $moduleName = MvcReg::getModuleName();
        $loginFormName = (is_null($loginFormName)) ? "loginForm" : $loginFormName;
        $loginForm = new LoginForm($moduleName, "system_login_form", $uidLabel, $pwdLabel, $insertHtmlString);
        
        return $loginForm;
    }
    
    

    
    protected function loginOut() {
        $moduleName = MvcReg::getModuleName();
        unset($_SESSION[$moduleName][Authentication::UID]);
        unset($_SESSION[$moduleName][Authentication::ENCRYPT_UID]);
        unset($_SESSION[$moduleName][Authentication::IS_LOGIN]);
        unset($_SESSION[Authentication::UID]['module']);
    }
    
}
?>
