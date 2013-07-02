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

require_once 'RuleInterface.php';

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
