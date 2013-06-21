<?php

/**
 * Description of StrPatternRule
 *
 * @author Hung-Fu Aaron Chang
 */
class StrPatternRule implements RuleInterface {
    //put your code here
   private $_pattern; 
   /**
    *
    * @param string $pattern 
    */ 
   public function setRule($pattern){
       $this->_pattern = $pattern;
   }
   /**
    *
    * @param string $input
    * @return boolean 
    */ 
   public function validRule($input) {
       if (!preg_match($this->_pattern, $input)) {
           return false;  
       }
       return true;
   }
    
}

?>
