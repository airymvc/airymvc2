<?php
require_once 'RuleInterface.php';
/**
 * Description of StrMaxRule
 *
 * @author Hung-Fu Aaron Chang
 */
class StrMaxLengthRule implements RuleInterface {

   private $_max; 
   /**
    *
    * @param string $pattern 
    */ 
   public function setRule($max){
       $this->_max = $max;
   }
   /**
    *
    * @param string $input
    * @return boolean 
    */ 
   public function validRule($input) {
       if (strlen($input) > $this->_max){
           return false;  
       }
       return true;
   }
}

?>
