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
    
    protected $_uidLabel = '%{AccountID}%';
    protected $_pwdLabel = '%{Pwd}%';
    protected $_insertHtmlString;
 

    public function __construct($moduleName, $id = null, $uidLabel = null, $pwdLabel = null, $insertHtmlString = null) {
        $id = (is_null($id)) ? "system_login_form" :  $id;
        parent::__construct($id);
        $signInActionName = Authentication::getSignInAction(MvcReg::getModuleName());
        $loginControllerName = Authentication::getLoginController(MvcReg::getModuleName());
        $form_action = PathService::getInstance()->getFormActionURL($moduleName, $loginControllerName, $signInActionName);
        if (!is_null($uidLabel) && !is_null($pwdLabel)) {
            $this->_uidLabel = $uidLabel;
            $this->_pwdLabel = $pwdLabel;        
        }
        $this->_insertHtmlString = $insertHtmlString;
        $this->createForm($moduleName, $form_action);
    }
    
    protected function createForm ($moduleName, $action) {  
        $acl = AclUtility::getInstance();
        $tbl_id = $acl->getTableIdByModule($moduleName);
        $mapFields = $acl->getMappingFieldByTbl($tbl_id);
        $uid_field = $mapFields["user_id"];
        $pwd_field = $mapFields["pwd"];
        
        $this->setAttribute("name", "login");
        $this->setAttribute("class", "login");
        $this->setAttribute("action", $action);
        
        $uidTxtField = new TextElement($uid_field);
        $pwdTxtField = new PasswordElement($pwd_field);
        $sBtn = new SubmitElement("submit");
        $html1 = new HtmlScript();
        $html1->setScript("<div id='login_form' class='login_form'>");
        $html2 = new HtmlScript();
        $html2->setScript("</div>");
        $inHtml = null;
        if (!is_null($this->_insertHtmlString)) {
            $inHtml = new HtmlScript();
            $inHtml->setScript($this->_insertHtmlString);
        }
        $uidTxtField->setLabel('uid', $this->_uidLabel, 'uid');
        $uidTxtField->setAttribute('name', $uid_field);
        $pwdTxtField->setLabel('pwd', $this->_pwdLabel, 'pwd');
        $pwdTxtField->setAttribute('name', $pwd_field);
        
        $this->setElement($html1);
        $this->setElement($uidTxtField);
        $this->setElement($pwdTxtField);
        if (!is_null($this->_insertHtmlString)) {
            $this->setElement($inHtml);
        }
        $this->setElement($sBtn);
        $this->setElement($html2);
    }
    
}