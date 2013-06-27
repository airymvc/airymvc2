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

/*
 * @TODO: Need to refactoring this. This controller shares several methods with AppController
 */



class AclController extends AbstractController {

    protected $_loginForm;
//    protected $model;
//    protected $view;
//    protected $params;
    protected $_registerViewName;
    protected $_uidLabel = null;
    protected $_pwdLabel = null;
    protected $_insertHtmlString;

//    const IS_LOGIN = "islogin";
//    const UID = "uid";

//    const VIEW_POSTFIX = 'View';
    const ACTION_POSTFIX = 'Action';
    protected $_auth;

    public function initial($params) {
        $this->setDefaultModel();
        $this->view = new AppView();
        $this->setDefaultView();
        $this->setParams($params);
        $this->_auth = new AuthElement();
        
    }



    /**
     * signIn, check with the database table with uid, pwd and mapping table
     * @params: String $uid, String $pwd, String $mapTbl
     */
    public function signInAction() {
        $this->_auth->signIn();
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
        $this->_auth->loginOut();
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

    protected function login($viewName = null, $loginFormName = null) {

        $loginForm = $this->_auth->prepareLoginForm($this->_uidLabel, $this->_pwdLabel, $this->_insertHtmlString);
        $moduleName = MvcReg::getModuleName();
        if (!is_null($viewName)) {
            $this->switchView($moduleName, $viewName);
        }
        $this->view->setVariable($loginFormName, $loginForm);
        $this->view->render();
    }

    function setDefaultView() {
        if (file_exists(MvcReg::getActionViewFile())) {
            $this->view->setViewFilePath(MvcReg::getActionViewFile());
        } else {
            $this->view->setViewFilePath(MvcReg::getViewFile());
        }
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
    
    protected function loginError($viewName = null, $errorMessage = null) {
        $moduleName = MvcReg::getModuleName();
        $loginForm = new LoginForm($moduleName, "system_login_form", $this->_uidLabel, $this->_pwdLabel, $this->_insertHtmlString);
        if (!is_null($viewName)) {
            $this->switchView($moduleName, $viewName);
        }
        $error_msg = is_null($errorMessage) ? "ERROR!!" : $errorMessage;
        $this->view->setVariable('error_msg', $error_msg);
        $this->view->setVariable("form", $loginForm);
        $this->view->render();
    }

//    function setParams($params) {
//        $this->params = $params;
//    }
//
//    function getParams() {
//        return $this->params;
//    }
//
//    /**
//     * @return the $model
//     */
//    public function getModel() {
//        return $this->model;
//    }
//
////    /**
////     * @return the $view
////     */
////    public function getView() {
////        return $this->view;
////    }
//
//    /**
//     * @param field_type $model
//     */
//    public function setModel($model) {
//        $this->model = $model;
//    }

//    /**
//     * @param field_type $view
//     */
//    public function setView($view) {
//        $this->view = $view;
//    }

//    public function switchView($moduleName, $viewName) {
//        $viewClassName = $viewName . self::VIEW_POSTFIX;
//        $viewFile = "project". DIRECTORY_SEPARATOR. "modules" . DIRECTORY_SEPARATOR . $moduleName . DIRECTORY_SEPARATOR
//                  . "views" . DIRECTORY_SEPARATOR . $viewClassName . ".php";
//        $this->view->setViewFilePath($viewFile);
//    }

//    public function switchToCallAction($actionName) {
//            $controllerName = MvcReg::getControllerName();
//            $moduleName = MvcReg::getModuleName();
//
//            $actionViewClassName = ucwords($actionName) . self::VIEW_POSTFIX;
//            $actionViewFile = "project". DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$moduleName .DIRECTORY_SEPARATOR. "views".DIRECTORY_SEPARATOR .$controllerName. DIRECTORY_SEPARATOR. $actionViewClassName .".php";
//            $absActionViewFile = PathService::getInstance()->getRootDir() . DIRECTORY_SEPARATOR . $actionViewFile;
//        
//            if (!file_exists($absActionViewFile)) {
//                $name = $controllerName . "_" . $actionName;
//                $actionViewClassName = $name . self::VIEW_POSTFIX;
//                $actionViewFile = "project". DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$moduleName .DIRECTORY_SEPARATOR. "views".DIRECTORY_SEPARATOR . $actionViewClassName .".php";
//            }
//            
//            MvcReg::setActionViewClassName($actionViewClassName);
//            MvcReg::setActionViewFile($actionViewFile); 
//            
//            $action = $actionName . self::ACTION_POSTFIX;
//            $this->setDefaultView();
//            $this->$action();
//    }
//
//    public function getCurrentActionURL() {
//        $moduleName = MvcReg::getModuleName();
//        $controllerName = MvcReg::getControllerName();
//        $actionName = MvcReg::getActionName();
//        $url = PathService::getInstance()->getFormActionURL($moduleName, $controllerName, $actionName);
//        return $url;
//    }

}
?>

