<?php
require_once 'RuleInterface.php';
/**
 * Description of StrMinRule
 *
 * @author Hung-Fu Aaron Chang
 */
class StrMinLengthRule implements RuleInterface {
    //put your code here
   private $_min; 
   /**
    *
    * @param string $pattern 
    */ 
   public function setRule($min){
       $this->_min = $min;
   }
   /**
    *
    * @param string $input
    * @return boolean 
    */ 
   public function validRule($input) {
       if (strlen($input) < $this->_min){
           return false;  
       }
       return true;
   }
}

?>
