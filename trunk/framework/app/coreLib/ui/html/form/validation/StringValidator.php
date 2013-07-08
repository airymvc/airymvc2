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
 * @author Hung-Fu Aaron Chang
 */

require_once '../../html/form/AbstractValidator.php/AbstractValidator.php';


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
