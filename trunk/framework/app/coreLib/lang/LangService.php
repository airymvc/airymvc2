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
 * Description of LangParser
 *
 * @author Hung-Fu Aaron Chang
 */
class LangService {
    //put your code here
    private $_config;
    private static $instance;
    
    function __construct($iniFilePath = null) {
        $this->_config = Config::getInstance($iniFilePath);
    }
    
    public static function getInstance($iniFilePath = null)
    {
        if(is_null(self::$instance)) {
            self::$instance = new self($iniFilePath);
        }    
        
        return self::$instance;
    }
    /**
     * Get the words from each language
     * @return array 
     */
    public function getLangaugeWord(){
        $langPath = $this->_config->getLanguageFolder();
        $root = PathService::getInstance()->getRootDir();
        $absLangPath = $root . DIRECTORY_SEPARATOR . $langPath;
        $ignore = array('.', '..', '.svn', '.DS_Store');
        $langArray = array();
	if ($handle = opendir($absLangPath)) {
            while (false !== ($file = readdir($handle))) {
                   $absFile = $absLangPath.DIRECTORY_SEPARATOR.$file;
                   $tLangArr = array();
                   if (!in_array($file, $ignore) && !is_dir($absFile)) {
                       try {
                          if (($tLangArr = @parse_ini_file($absFile, true)) == false) { 
                              throw new Exception('Cannot Parse INI file: ' . $absFile);
                          }
                       } catch (Exception $e) {
                              error_log($e->getMessage());
                       }
                       $langArray = array_merge($langArray, $tLangArr);
                   }
            }
        }
        return $langArray;
    }
    /**
     * @param String $key
     * @param String $langCode
     * @return String 
     */
    public function getWord($key, $langCode)
    {
        $words = $this->getLangaugeWord();
        if(!isset($words[$langCode][$key])) {
           return null;
        }
        
        return $words[$langCode][$key]; 
    }
    /**
     *
     * @param String $word
     * @param String $fromLangCode
     * @param String $toLangCode
     * @return String 
     */
    public function getTranslation($word, $fromLangCode, $toLangCode)
    {
        $words = $this->getLangaugeWord();
        if(!isset($words[$fromLangCode]) || !isset($words[$toLangCode])) {
           return null;
        }
        $fromWords = $words[$fromLangCode];
        
        $wdKey = null;
        foreach ($fromWords as $key => $wdValue) {
            if ($wdValue == $word) {
                $wdKey = $key;
            }
        }
        if (!isset($words[$toLangCode][$wdKey])) {
             return null;
        }
        
        return $words[$toLangCode][$wdKey]; 
    }
    
}

?>
