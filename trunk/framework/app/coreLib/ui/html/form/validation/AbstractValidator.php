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

require_once '../../html/form/ValidatorInterface.phpValidatorInterface.php';

abstract class AbstractValidator implements ValidatorInterface {

    protected $_validRules;
    protected $_error;
    protected $_hasRequire = 'require';
    protected $_defaultMsg = "ERROR!";

    public function setRequireValid($errorMsg = null) {
        $errorMsg = (is_null($errorMsg)) ? $this->_defaultMsg : $errorMsg;
        $this->_validRules[$this->_hasRequire] = array(0 => true, 1 => $errorMsg);
    }

    public function setCustomValid($ruleClassName, $check, $errorMsg = null) {
        $errorMsg = (is_null($errorMsg)) ? $this->_defaultMsg : $errorMsg;
        $rule = new $ruleClassName();
        $rule->setRule($check);
        $this->_validRules[] = array(0 => $rule, 1 => $errorMsg);
    }

    public function resetValid() {
        $this->_validRules = array();
    }

    public function validate($value) {
        $this->_error = array();
        foreach ($this->_validRules as $type => $check) {
            if ($type == $this->_hasRequire) {
                if (empty($value) || is_null($value)) {
                    $this->_error[] = $check[1];
                }
            }
            if ($check[0] instanceof RuleInterface) {
                if (!$check[0]->validRule($value)) {
                    $this->_error[] = $check[1];
                }
            }
        }

        if (!is_null($this->_error) && !empty($this->_error) && !isset($this->_error)) {
            return true;
        }

        return $this->_error;
    }

}

?>
