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

    protected $_loginForm;
    protected $_loginFormVariableName;
    protected $_acl;

    public function initial($params, $viewVariables = null) {
    	parent::initial($params, $viewVariables);
    	$this->_acl = new AclComponent();
    	$this->_loginFormVariableName = $this->_loginForm->getFormId();
    } 

    /**
     * signIn, check with the database table with uid, pwd and mapping table
     * @params: String $uid, String $pwd, String $mapTbl
     */
    public function signInAction() {
        $this->_acl->signIn();
    }
    
    public function logoutAction() {
        $this->_acl->loginOut();
    }
    
    public function loginErrorAction() {
        $this->_acl->loginError();
    }

    public function loginAction() {
        $this->_acl->login();
    }



    
//    protected function signIn($moduleName = null, $controllerName = null, $actionName = null) {
//         $this->_acl->signIn($moduleName, $controllerName, $actionName);   	
//    }
    
    
//    protected function login($loginFormName = null) {
//    	if (!is_null($loginFormName)) {
//    		$this->setLoginFormVariableName($loginFormName);
//    	}
//    	$this->view->setVariable($this->_loginFormVariableName, $this->_loginForm);
//    	$this->view->render();
//    }
//    
//    protected function loginError($viewName = null, $errorMessage = null) {
//        $moduleName = MvcReg::getModuleName();
//        $this->_loginForm->populateErrorMessage($errorMessage);
//        
//        if (!is_null($viewName)) {
//            $this->switchView($moduleName, $viewName);
//        }
//        $errorMessage = is_null($errorMessage) ? "ERROR!!" : $errorMessage;
//        $this->view->setVariable('loginErrorMessage', $errorMessage);
//        $this->view->setVariable($this->_loginFormVariableName, $this->_loginForm);
//        $this->view->render();
//    }
    
    /**
     * Login Form setter functions
     */
    public function setLoginFormVariableName($loginFormVariableName) {
    	$this->_loginFormVariableName = $loginFormVariableName;
    }
    
//    public function resetLoginForm($moduleName = null, $formId = null, $formName = null, $uidLabel = null, $pwdLabel = null, $formLayout = null, $loginMsgId = null) {
//        $this->_acl->resetLoginForm($moduleName, $formId, $formName, $uidLabel, $pwdLabel, $formLayout, $loginMsgId);
//    }
}
?>

