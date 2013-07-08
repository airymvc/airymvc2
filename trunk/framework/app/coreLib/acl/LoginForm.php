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
    protected $_formLayout;
 

    public function __construct($moduleName, $formId = null, $formName= null, $uidLabel = null, $pwdLabel = null, $formLayout = null) {
        $formId   = (is_null($formId)) ? "system_login_form" :  $formId;
        $formName = (is_null($formName)) ? "system_login_form" :  $formName;
        parent::__construct($formId);
        $signInActionName = Authentication::getSignInAction(MvcReg::getModuleName());
        $loginControllerName = Authentication::getLoginController(MvcReg::getModuleName());
        $formAction = PathService::getInstance()->getFormActionURL($moduleName, $loginControllerName, $signInActionName);
        if (!is_null($uidLabel) && !is_null($pwdLabel)) {
            $this->_uidLabel = $uidLabel;
            $this->_pwdLabel = $pwdLabel;        
        }
        $this->_formLayout = $formLayout;
        $this->createForm($moduleName, $formAction, $formId, $formName, $formLayout);
    }
    
    protected function createForm ($moduleName, $action, $formId, $formName, $formLayout) {  
        $acl = AclUtility::getInstance();
        $tblId = $acl->getTableIdByModule($moduleName);
        $mapFields = $acl->getMappingFieldByTbl($tblId);
        $uidField = $mapFields["user_id"];
        $pwdField = $mapFields["pwd"];
        //set form
        $this->setAttribute("name", $formName);
        $this->setAttribute("class", $formName);
        $this->setAttribute("action", $action);
        //set form elements
        $uidTxtField = new TextElement($uidField);
        $pwdTxtField = new PasswordElement($pwdField);
        $submitBtn = new SubmitElement("submit");
        
        $uidTxtField->setLabel('uid', $this->_uidLabel, 'uid');
        $uidTxtField->setAttribute('name', $uidField);
        $pwdTxtField->setLabel('pwd', $this->_pwdLabel, 'pwd');
        $pwdTxtField->setAttribute('name', $pwdField);
        
        //set default form layout here
        if (is_null($formLayout)) {
        	$formLayout = array($formId => array("<div class='{$formName}' name='{$formId}'", "</div>"));
        }
        
        $this->setFormLayout($formLayout);
        $this->setElement($uidTxtField);
        $this->setElement($pwdTxtField);
        $this->setElement($submitBtn);
    }
    
}