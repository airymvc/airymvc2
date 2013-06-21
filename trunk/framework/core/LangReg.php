<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
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
