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

/**
 * Description of LangReg
 *
 * @author Hung-Fu Aaron Chang
 */
class LangReg {
    
     static $_languageCode;
     
     public static function setLanguageCode($languageCode)
     {
          self::$_languageCode = $languageCode;
     }
     public static function getLanguageCode()
     {
          return self::$_languageCode;
     }

}

?>
