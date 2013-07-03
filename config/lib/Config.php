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
 * @author: Hung-Fu Aaron Chang
 */

class Config{
    
    private static $instance;
    private $_iniFilePath;
    
    const JS_INI_KEY  = 'jsconfig';
    const CS_INI_SKEY = 'cssconfig';
    const DB_INI_SKEY = 'dbconfig';
    
    const JSKEY       = 'script';
    const CSSKEY      = 'css';
    
    function __construct($iniFilePath = null) 
    {
        $root = PathService::getInstance()->getRootDir();
        if (is_null($iniFilePath)) {
            $this->_iniFilePath = $root . DIRECTORY_SEPARATOR . 'project' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.ini';
            //Fallback config path to framework's level config folder's config.ini
            $frameworkConfig = $root . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.ini';
            if (!file_exists($this->_iniFilePath)) {
                $this->_iniFilePath = $frameworkConfig;    
            }
             
        } else {
            $this->_iniFilePath = $iniFilePath;
        }
    }
    
    /**
     *  Use Singleton pattern here
     */
    public static function getInstance($iniFilePath = null)
    {
        if(is_null(self::$instance)) {
            self::$instance = new self($iniFilePath);
        }    
        
        return self::$instance;
    }
	
    /**
     * The result depends on multiple databases
     * [0] => array of database #1 setting
     * [1] => array of database #2 setting
     * 
     * @return array 
     */
    public function getDBConfig()
    {
         $iniArray = parse_ini_file ($this->_iniFilePath, true);
         $dbArray = $iniArray['DB'];
         
         $result   = array();
         $parseIni = $this->convertMultiIni($dbArray);
         foreach ($parseIni[self::DB_INI_SKEY] as $mkey => $kv) {
             $tmpArray = array();
             foreach ($kv as $key => $value) {
                  $tmpArray[$key] = $value;
             }
             $result[] = $tmpArray;
         }
         //TODO: now only consider one database situation. Need to consider multiple databases
         return $result;
     }
     
     public function getTimezone()
     {
     	 $iniArray = parse_ini_file ($this->_iniFilePath, true);
         if (!isset($iniArray['Time_Zone']) || !isset($iniArray['Time_Zone']['timezone'])) {
             return null;
         }       
         $tzArray = $iniArray['Time_Zone'];
         
         return $tzArray['timezone'];
     }
     public function getAuthenticationConfig()
     {
     	 $iniArray = parse_ini_file ($this->_iniFilePath, true);
         $auArray = $iniArray['Authentication'];
         
         return $auArray;
     }
     public function getMVCKeyword()
     {
     	 $iniArray = parse_ini_file ($this->_iniFilePath, true);
         if(!isset($iniArray['MVC_Keyword']))
         {
            return null;
         }
         $mvcArray = $iniArray['MVC_Keyword'];
         
         return $mvcArray;
     }
     public function getDefaultModule()
     {
     	 $iniArray = parse_ini_file ($this->_iniFilePath, true);
         $modules = $iniArray['Module'];

         $defaultModule = $modules["default"];

         return $defaultModule;
     }
     
     public function getLanguage()
     {
     	 $iniArray = parse_ini_file ($this->_iniFilePath, true);
         $language = $iniArray['Language'];

         return $language;
     }
     public function getLeadFile()
     {
     	 $iniArray = parse_ini_file ($this->_iniFilePath, true);
         if (!isset($iniArray['Lead_File'])) {
             return "index.php";
         }
         
         return $iniArray['Lead_File'];
     }
     public function getModuleKeyword()
     {
         $mvcArray = $this->getMVCKeyword();
         if (isset($mvcArray['module'])&& !is_null($mvcArray)) {
             return $mvcArray['module'];
         }
         
         return 'module';
     }
     
     public function getControllerKeyword()
     {
         $mvcArray = $this->getMVCKeyword();
         if (isset($mvcArray['controller']) && !is_null($mvcArray)) {
             return $mvcArray['controller'];
         }
         return 'controller';       
     }
     public function getActionKeyword()
     {
         $mvcArray = $this->getMVCKeyword();
         if (isset($mvcArray['action'])&& !is_null($mvcArray)) {
             return $mvcArray['action'];
         }
         return 'action';        
     }
     
     public function getDefaultLanguage()
     {
         $langArray = $this->getLanguage();
         if (isset($langArray['default'])) {
             return $langArray['default'];
         }
         return 'en-US';        
     }
     
     public function getLanguageFolder()
     {
         $langArray = $this->getLanguage();
         if (isset($langArray['folder']) && !empty($langArray['folder'])) {
             return $langArray['folder'];
         }
         return 'lang';        
     }
     
     public function getLanguageKeyword()
     {
         $lang_array = $this->getLanguage();
         if (isset($lang_array['keyword']) && !empty($lang_array['keyword'])) {
             return $lang_array['keyword'];
         }
         return 'lang';        
     }
     public function getLeadFileName()
     {
         $lead_file_array = $this->getLeadFile();
         if (isset($lead_file_array['filename']) && !empty($lead_file_array['filename'])) {
             return $lead_file_array['filename'];
         }
         return 'index.php';        
     }
     /**
      * The result will be array('script' => zero base array, 'css' => zero base array);
      * @return array 
      */
     public function getScriptPlugin()
     {
     	 $ini_array = parse_ini_file ($this->_iniFilePath, true);
         if (!isset($ini_array['JS_Plugin'])) {
             return null;
         }
         $result = array();
         foreach ($ini_array['JS_Plugin'] as $key => $value) {
              $configs = explode('.', $key);
              if ($configs[0] == self::CS_INI_SKEY) {
                  $result[self::CSSKEY][] = $value;
              }
              if ($configs[0] == self::JS_INI_KEY) {
                  $result[self::JSKEY][] = $value;
              }                      
         }
    
         return $result;         
     }
     
     /**
      * Construct the array of ini array
      * Input key-value pairs
      * 
      * array $ini
      * @return array 
      */
     private function convertMultiIni ($keyValues) {
        
        $result = array();
        foreach($keyValues as $key => $value)
        {
            $tmp = &$result;
            foreach(explode('.', $key) as $k) {
                $tmp = &$tmp[$k];
            }
            $tmp = $value;
        }
        unset($tmp);

        return $result; 
     }
}
?>
