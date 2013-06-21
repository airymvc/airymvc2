<?php
require_once 'AbstractValidator.php';
/**
 * Description of Validator
 *
 * @author Hung-Fu Aaron Chang
 */
class StringValidator extends AbstractValidator{
        
    public function setMaxLengthValid($maxLength, $errorMsg = null) {
        $errorMsg = (is_null($errorMsg)) ? $this->_defaultMsg : $errorMsg;
        $rule = new StrMaxLengthRule();
        $rule->setRule($maxLength);
        $this->_validRules[] = array(0 => $rule, 1 => $errorMsg);
    }
    
    public function setMinLengthValid($minLength, $errorMsg = null) {
        $errorMsg = (is_null($errorMsg)) ? $this->_defaultMsg : $errorMsg;
        $rule = new StrMinLengthRule();
        $rule->setRule($minLength);
        $this->_validRules[] = array(0 => $rule, 1 => $errorMsg);
    } 
    
    public function setPatternValid($pattern ,$errorMsg = null) {
        $errorMsg = (is_null($errorMsg)) ? $this->_defaultMsg : $errorMsg;
        $rule = new StrPatternRule();
        $rule->setRule($pattern);
        $this->_validRules[] = array(0 => $rule, 1 => $errorMsg);
    }       

}

?>
