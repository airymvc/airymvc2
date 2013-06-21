<?php


/**
 * Description of ValidatorInterface
 *
 * @author changA
 */
interface ValidatorInterface {
    
    public function setRequireValid($errorMsg = null);
    
    public function setCustomValid($methodName, $object, $errorMsg = null);
    
    public function validate($value);
    
}

?>
