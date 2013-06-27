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
 *
 */

require_once 'RuleInterface.php';
/**
 * Description of EmailRule
 *
 * @author Hung-Fu Aaron Chang
 */
class EmailRule implements RuleInterface{
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
