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

class LoginForm extends PostForm{
    
    protected $_uidLabel = '%{Username}%';
    protected $_pwdLabel = '%{Pwd}%';
    protected $_formDecoration;
 

    public function __construct($formId = null, $formName= null, $uidLabel = null, $pwdLabel = null, $moduleName = null, $formDecoration = null, $loginMsgId = null) {
        $formId   = (is_null($formId)) ? "system_login_form" :  $formId;
        $formName = (is_null($formName)) ? $formId :  $formName;
        $loginMsgId = (is_null($loginMsgId)) ? "system_login_message" : $loginMsgId;
        parent::__construct($formId);
        
        $moduleName = (!is_null($moduleName)) ? $moduleName : MvcReg::getModuleName();
        $signInActionName = Authentication::getSignInAction($moduleName);
        $loginControllerName = Authentication::getLoginController($moduleName);
        
        $formAction = PathService::getInstance()->getFormActionURL($moduleName, $loginControllerName, $signInActionName);
        if (!is_null($uidLabel) && !is_null($pwdLabel)) {
            $this->_uidLabel = $uidLabel;
            $this->_pwdLabel = $pwdLabel;        
        }
        $this->_formDecoration = $formDecoration;
        $this->createForm($formAction, $formId, $formName, $uidLabel, $pwdLabel, $moduleName, $loginMsgId);
    }
    
    protected function createForm ($formAction, $formId, $formName, $moduleName, $loginMsgId) {  
        $acl = AclUtility::getInstance();
        $tblId = $acl->getTableIdByModule($moduleName);
        $mapFields = $acl->getMappingFieldByTbl($tblId);
        $uidField = $mapFields["user_id"];
        $pwdField = $mapFields["pwd"];
        //set form
        $this->setAttribute("name", $formName);
        $this->setAttribute("class", $formName);
        $this->setAttribute("action", $formAction);
        //set form elements
        $uidTxtField = new TextElement($uidField);
        $pwdTxtField = new PasswordElement($pwdField);
        $submitBtn = new SubmitElement("submit");
        $messageDiv = new DivElement($loginMsgId);
        
        $uidTxtField->setLabel('uid', $this->_uidLabel, 'uid');
        $uidTxtField->setAttribute('name', $uidField);
        $pwdTxtField->setLabel('pwd', $this->_pwdLabel, 'pwd');
        $pwdTxtField->setAttribute('name', $pwdField);
        
        //set default form layout here
        if (is_null($this->_formDecoration)) {
        	$this->_formDecoration = array($formId => array("<div class='{$formName}' name='{$formId}'", "</div>"));
        }
        
        $this->setFormLayout($this->_formDecoration);
        $this->setElement($uidTxtField);
        $this->setElement($pwdTxtField);
        $this->setElement($submitBtn);
    }
    
}