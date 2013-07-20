<?php
/**
 * AiryMVC Framework
 * 
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

require_once dirname(__FILE__) . '/../ui/html/form/PostForm.php';
require_once dirname(__FILE__) . '/../ui/html/UIComponent.php';
require_once dirname(__FILE__) . '/../../../core/MvcReg.php';
require_once dirname(__FILE__) . '/Authentication.php';

class LoginForm extends PostForm {
    
	//Default form value
    const DEFAULT_UID = '%{Username}%';
    const DEFAULT_PWD = '%{Pwd}%';
    const DEFAULT_LOGIN_FORM_ID = "system_login_form";
    const DEFAULT_LOEGIN_MESSAGE_ID = "system_login_message";
    
    protected $_formDecoration;
    protected $_formId;
    protected $_loginMsgId;
 

    public function __construct($formId = null, $formName= null, $uidLabel = null, $pwdLabel = null, $moduleName = null, $formDecoration = null, $loginMsgId = null, $formAction = null) {
        $this->_formId   = (is_null($formId)) ? self::DEFAULT_LOGIN_FORM_ID : $formId;
        $formName = (is_null($formName)) ? $this->_formId :  $formName;
        $this->_loginMsgId = (is_null($loginMsgId)) ? self::DEFAULT_LOEGIN_MESSAGE_ID : $loginMsgId;
        parent::__construct($formId);
        
        $moduleName = (!is_null($moduleName)) ? $moduleName : MvcReg::getModuleName();
        $signInActionName = Authentication::getSignInAction($moduleName);
        $loginControllerName = Authentication::getLoginController($moduleName);
        
        $formAction = (is_null($formAction)) ? PathService::getFormActionURL($moduleName, $loginControllerName, $signInActionName) : $formAction;
        
        if (is_null($uidLabel) || $uidLabel == "") {
            $uidLabel = self::DEFAULT_UID;      
        }
        if (is_null($pwdLabel) || $pwdLabel == "") {
            $pwdLabel = self::DEFAULT_PWD;        
        }
        $this->_formDecoration = $formDecoration;
        $this->createForm($formAction, $this->_formId, $formName, $uidLabel, $pwdLabel, $moduleName, $this->_loginMsgId);
    }
    
    protected function createForm ($formAction, $formId, $formName, $uidLabel, $pwdLabel, $moduleName, $loginMsgId) {  
        $acl = AclUtility::getInstance();
        $tblId = $acl->getTableIdByModule($moduleName);
        $mapFields = $acl->getMappingFieldByTbl($tblId);
        $uidField = $mapFields["user_id"];
        $pwdField = $mapFields["pwd"];
        
        //set form
        $this->setAttribute("id", $formName);
        $this->setAttribute("name", $formName);
        $this->setAttribute("class", $formName);
        $this->setAttribute("action", $formAction);
        //set form elements
        $uidTxtField = new TextElement($uidField);
        $pwdTxtField = new PasswordElement($pwdField);
        $submitBtn = new SubmitElement("submit");
        $messageDiv = new DivElement($loginMsgId);
        
        $uidTxtField->setLabel('uid', $uidLabel, 'uid');
        $uidTxtField->setAttribute('name', $uidField);
        $pwdTxtField->setLabel('pwd', $pwdLabel, 'pwd');
        $pwdTxtField->setAttribute('name', $pwdField);
        
        //set default form layout here
        if (is_null($this->_formDecoration)) {
        	$this->_formDecoration = array($formId => array("<div class='{$formName}' name='{$formId}'>", "</div>"));
        }
        
        $this->setDecoration($this->_formDecoration);
        $this->setElement($uidTxtField);
        $this->setElement($pwdTxtField);
        $this->setElement($messageDiv);
        $this->setElement($submitBtn);
    }
    
    
    public function populateErrorMessage($message) {
    	$divElement = $this->getElementById($this->_loginMsgId);
    	$divElement->setHtmlValue($message);
    }
    
    public function getFormId() {
    	return $this->_formId;
    }
    
}