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

require_once '../../form/validation/AbstractValidator.phpactValidator.php';

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
