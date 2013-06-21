<?php
require_once 'AbstractValidator.php';
/**
 * Description of EmailValidator
 *
 * @author Hung-Fu Aaron Chang
 */
class EmailValidator extends AbstractValidator{
  
    

    protected $_hasEmail = "email";
    private $_defaultPattern = "/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/";
   
    
    public function setEmailValid($pattern = null ,$errorMsg = null) {
        $errorMsg = (is_null($errorMsg)) ? $this->_defaultMsg : $errorMsg;
        $pattern = (is_null($pattern)) ? $this->_defaultPattern : $pattern;
        $rule = new EmailRule();
        $rule->setRule($pattern);
        $this->_validRules[] = array(0 => $rule, 1 => $errorMsg);
    } 
    
    
    


}

?>
