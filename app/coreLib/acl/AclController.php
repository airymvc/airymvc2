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

/*
 * @TODO: Need to refactoring this. This controller shares several methods with AppController
 */



class AclController extends AbstractController {

//    protected $_loginForm;
//    protected $_loginFormVariableName;
//    protected $_loginFormId;
//    protected $_registerViewName;
//    protected $_uidLabel = null;
//    protected $_pwdLabel = null;
//    protected $_loginMsgId;

    protected $_acl;

    public function initial($params) {
    	parent::initial($params);
    	$this->_acl = new AclComponent();
    } 

    /**
     * signIn, check with the database table with uid, pwd and mapping table
     * @params: String $uid, String $pwd, String $mapTbl
     */
    public function signInAction() {
        $this->_acl->signIn();
    }

    public function registerAction() {
        $this->register();
    }

    public function loginErrorAction() {
        $this->loginError();
    }

    public function loginAction() {
        $this->view->setVariable($this->_loginFormVariableName, $this->_loginForm);
        $this->view->render();
    }

    public function logoutAction() {
        $this->_acl->loginOut();
    }

    public function setRegisterViewName($viewName) {
        $this->_registerViewName = $viewName;
    }

    public function setFormLabels($uidLabel, $pwdLabel) {
        $this->_uidLabel = $uidLabel;
        $this->_pwdLabel = $pwdLabel;
    }

    protected function login($loginFormName = null) {


    }

    protected function register($viewName = null) {

        $this->view->render();
    }
    
    protected function loginError($viewName = null, $errorMessage = null) {
        $moduleName = MvcReg::getModuleName();
        //TODO: this loginform needs to have a way to contain the error message.... remove %this->_insertString
        $loginForm = new LoginForm($moduleName, "system_login_form", $this->_uidLabel, $this->_pwdLabel, $this->_insertHtmlString);
        if (!is_null($viewName)) {
            $this->switchView($moduleName, $viewName);
        }
        $error_msg = is_null($errorMessage) ? "ERROR!!" : $errorMessage;
        $this->view->setVariable('error_msg', $error_msg);
        $this->view->setVariable("form", $loginForm);
        $this->view->render();
    }



}
?>

