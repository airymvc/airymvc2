<?php

/*
 * @TODO: Need to refactoring this. This controller shares several methods with AppController
 */

require_once ('AcDb.php');
require_once ('AclUtility.php');

class AclController extends AbstractController {

    protected $_loginForm;
    protected $model;
    protected $view;
    protected $params;
    protected $_registerViewName;
    protected $_uidLabel = null;
    protected $_pwdLabel = null;
    protected $_insertHtmlString;

//    const IS_LOGIN = "islogin";
//    const UID = "uid";

    const VIEW_POSTFIX = 'View';
    const ACTION_POSTFIX = 'Action';

    public function initial($params) {
        $this->setDefaultModel();
        $this->view = new AppView();
        $this->setDefaultView();
        $this->setParams($params);
    }

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

    /**
     * signIn, check with the database table with uid, pwd and mapping table
     * @params: String $uid, String $pwd, String $mapTbl
     */
    public function signInAction() {
        $this->signIn();
    }

    public function registerAction() {
        $this->register();
    }

    public function loginErrorAction() {
        $this->loginError();
    }

    public function loginAction() {
        $this->login();
    }

    public function logoutAction() {
        $this->loginOut();
    }

    public function setRegisterViewName($viewName) {
        $this->_registerViewName = $viewName;
    }

    public function setFormLabels($uidLabel, $pwdLabel) {
        $this->_uidLabel = $uidLabel;
        $this->_pwdLabel = $pwdLabel;
    }

    public function setLoginFormInsertHtml($insertHtmlString) {
        $this->_insertHtmlString = $insertHtmlString;
    }

    protected function register($viewName = null) {

        /**
         * @todo: Need to put error log while two view name are both null 
         */
        $registerViewName = (is_null($viewName)) ? $this->_registerViewName : $viewName;
        $moduleName = MvcReg::getModuleName();
        $this->switchView($moduleName, $registerViewName);
        $this->view->render();
    }

    protected function login($viewName = null, $loginFormName = null) {

        $loginFormName = (is_null($loginFormName)) ? "loginForm" : $loginFormName;
        $moduleName = MvcReg::getModuleName();
        $loginForm = new LoginForm($moduleName, "system_login_form", $this->_uidLabel, $this->_pwdLabel, $this->_insertHtmlString);
        if (!is_null($viewName)) {
            $this->switchView($moduleName, $viewName);
        }
        $this->view->setVariable($loginFormName, $loginForm);
        $this->view->render();
    }

    protected function loginError($viewName = null) {
        $moduleName = MvcReg::getModuleName();
        $loginForm = new LoginForm($moduleName, "system_login_form", $this->_uidLabel, $this->_pwdLabel, $this->_insertHtmlString);
        if (!is_null($viewName)) {
            $this->switchView($moduleName, $viewName);
        }
        $error_msg = "用戶名或密碼錯誤!!";
        $this->view->setVariable('error_msg', $error_msg);
        $this->view->setVariable("form", $loginForm);
        $this->view->render();
    }

    protected function loginOut() {
        $moduleName = MvcReg::getModuleName();
        unset($_SESSION[$moduleName][Authentication::UID]);
        unset($_SESSION[$moduleName][Authentication::ENCRYPT_UID]);
        unset($_SESSION[$moduleName][Authentication::IS_LOGIN]);
        unset($_SESSION[Authentication::UID]['module']);
    }

    function setDefaultView() {
        if (file_exists(MvcReg::getActionViewFile())) {
            $this->view->setViewFilePath(MvcReg::getActionViewFile());
        } else {
            $this->view->setViewFilePath(MvcReg::getViewFile());
        }
    }

    function setDefaultModel() {
        if (file_exists(MvcReg::$_modelFile)) {
            require_once (MvcReg::$_modelFile);
            $this->model = new MvcReg::$_modelClassName();
        }
    }

    function setParams($params) {
        $this->params = $params;
    }

    function getParams() {
        return $this->params;
    }

    /**
     * @return the $model
     */
    public function getModel() {
        return $this->model;
    }

    /**
     * @return the $view
     */
    public function getView() {
        return $this->view;
    }

    /**
     * @param field_type $model
     */
    public function setModel($model) {
        $this->model = $model;
    }

    /**
     * @param field_type $view
     */
    public function setView($view) {
        $this->view = $view;
    }

    public function switchView($moduleName, $viewName) {
        $viewClassName = $viewName . self::VIEW_POSTFIX;
        $viewFile = "modules" . DIRECTORY_SEPARATOR . $moduleName . DIRECTORY_SEPARATOR
                  . "views" . DIRECTORY_SEPARATOR . $viewClassName . ".php";
        $this->view->setViewFilePath($viewFile);
    }

    public function switchToCallAction($actionName) {
            $controllerName = MvcReg::getControllerName();
            $moduleName = MvcReg::getModuleName();

            $actionViewClassName = ucwords($actionName) . self::VIEW_POSTFIX;
            $actionViewFile = "modules".DIRECTORY_SEPARATOR.$moduleName .DIRECTORY_SEPARATOR. "views".DIRECTORY_SEPARATOR .$controllerName. DIRECTORY_SEPARATOR. $actionViewClassName .".php";
            $absActionViewFile = PathService::getInstance()->getRootDir() . DIRECTORY_SEPARATOR . $actionViewFile;
        
            if (!file_exists($absActionViewFile)) {
                $name = $controllerName . "_" . $actionName;
                $actionViewClassName = $name . self::VIEW_POSTFIX;
                $actionViewFile = "modules".DIRECTORY_SEPARATOR.$moduleName .DIRECTORY_SEPARATOR. "views".DIRECTORY_SEPARATOR . $actionViewClassName .".php";
            }
            
            MvcReg::setActionViewClassName($actionViewClassName);
            MvcReg::setActionViewFile($actionViewFile); 
            
            $action = $actionName . self::ACTION_POSTFIX;
            $this->setDefaultView();
            $this->$action();
    }

    public function getCurrentActionURL() {
        $moduleName = MvcReg::getModuleName();
        $controllerName = MvcReg::getControllerName();
        $actionName = MvcReg::getActionName();
        $url = PathService::getInstance()->getFormActionURL($moduleName, $controllerName, $actionName);
        return $url;
    }

}
?>

