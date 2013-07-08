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

require_once ('AcDb.php');
require_once ('AclUtility.php');

class AclComponent {
	
	const MD5 = "MD5";
    
    /**
     * signIn, check with the database table with uid, pwd and mapping table
     * @params: String $uid, String $pwd, String $mapTbl
     */
    public function signIn($moduleName = null, $controllerName = null, $actionName = null) {

        $moduleName = MvcReg::getModuleName();
        $controllerName = MvcReg::getControllerName();
        $actionName = MvcReg::getActionName();
        
        $acl = AclUtility::getInstance();
        $tbl_id = $acl->getTableIdByModule($moduleName);
        $AcDb = new AcDb();

        $tableName = $acl->getTableById($tbl_id);
        $mapFields = $acl->getMappingFieldByTbl($tbl_id);
        
        //prepare encryption setting
        $encrytion = $acl->getEncrytion();
        $useEcryption = $encrytion['$use_pwd_encryption'];
        $encrytionOption = $encrytion['encrytion_option'];
        $encrytionMethod = $encrytion['encrytion_method'];        

        $dbUid = $mapFields["user_id"];
        $dbPwd = (isset($mapFields["pwd"])) ? $mapFields["pwd"] : null;
        $dbSalt = (isset($mapFields["pwd_encrypt"])) ? $mapFields["pwd_encrypt"] : null;
        $dbIsdelete = (isset($mapFields["is_delete"])) ? $mapFields["is_delete"] : null;
        $dbIsdeleteValue = (isset($mapFields["is_delete_value"]) && !is_null($dbIsdelete)) ? $mapFields["is_delete_value"] : null;

        $uid = $this->params["{$dbUid}"];
        $pwd = $this->params["{$dbPwd}"];

        $mysql_results = null;
        //determine use encryption for password or not 
        if (!is_null($useEcryption) && ($useEcryption == 1)) {
        	$salt = "";
        	if (strtoupper($encrytionOption) == "PHP") {
        		/**
        		 * Currently, only support MD5
        		 */
        		if (strtoupper($encrytionMethod) == self::MD5) {
        			$salt = md5(trim($pwd));
        		}
        	} else {
        		$encryObj = new $encrytionOption();
        		$salt = $encryObj->$encrytionMethod(trim($pwd));
        	}
            $mysql_results = $AcDb->getUserByUidPwd($tableName, $dbUid, $uid, $dbSalt, $dbIsdelete, $dbIsdeleteValue);
        } else {
            $mysql_results = $AcDb->getUserByUidPwd($tableName, $dbUid, $uid, $dbPwd, $dbIsdelete, $dbIsdeleteValue);
        }
        $rows = mysql_fetch_array($mysql_results, MYSQL_ASSOC);
        $bLogin = false;
        
        if (!is_null($useEcryption) && ($useEcryption == 1)) {
            if ($rows[$dbSalt] == $salt) {
                $bLogin = true;
            }
        } else {
            if ($rows[$dbPwd] == $pwd) {
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
            //forward to login sucessful action - this is set in the act.xml
            Dispatcher::forward($moduleName, $successfulController, $successfulAction, $this->params);
        } else {
            $authArray = $acl->getAuthentications();
            $loginErrorAction = "loginErrorAction";
            if (isset($authArray[$moduleName]['login_error_action'])) {
                $loginErrorActionName = $authArray[$moduleName]['login_error_action'];
                $loginErrorAction = $loginErrorActionName . self::ACTION_POSTFIX;
            } 
            //forward to login error action
            Dispatcher::forward($moduleName, $controllerName, $actionName, $this->params);
        }
    }
    
    
    public function prepareLoginForm($moduleName = null, $formId = null, $formName = null ,$uidLabel = null, $pwdLabel = null, $formLayout = null) {

        $moduleName = (is_null($moduleName)) ? MvcReg::getModuleName() : $moduleName;
        $formName = (is_null($formName)) ? "system_login_form" : $formName;
        $formId = (is_null($formId)) ? "system_login_form" : $formId;
        $uidLabel = (is_null($uidLabel)) ? "%{Username}%" : $uidLabel;
        $pwdLabel = (is_null($uidLabel)) ? "%{Password}%" : $pwdLabel;
        
   		 /**
     	  * FormLayout example:
     	  * 
     	  * array(formId      => array('<div class="class_selector">', '</div>'),
     	  *       elementId1  => array('<div class="elememtClass1">', '</div>'),
     	  *       elementId2  => array('<div class="elememtClass2">', '</div>'),
    	  *       ...
     	  *       {elementId} => array('{open_html}, {close_html})
     	  *      );
     	  *      
     	  */            
        $loginForm = new LoginForm($moduleName, $formId, $formName, $uidLabel, $pwdLabel, $formLayout);
        
        return $loginForm;
    }
    
    

    
    public function loginOut() {
        $moduleName = MvcReg::getModuleName();
        unset($_SESSION[$moduleName][Authentication::UID]);
        unset($_SESSION[$moduleName][Authentication::ENCRYPT_UID]);
        unset($_SESSION[$moduleName][Authentication::IS_LOGIN]);
        unset($_SESSION[Authentication::UID]['module']);
    }
    
}
?>
