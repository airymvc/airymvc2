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
    
    const SINGLE_DB_SETTING_DB_ID = 'db1';
    const JS_INI_KEY  = 'jsconfig';
    const CS_INI_SYS_KEY = 'cssconfig';
    const DB_INI_SYS_KEY = 'dbconfig';
    
    const JSKEY       = 'script';
    const CSSKEY      = 'css';
    
    function __construct() 
    {
        $root = PathService::getRootDir();
        //Read the project's config first
		//This is the project's config
        $this->_iniFilePath = $root . DIRECTORY_SEPARATOR . 'project' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.ini';
        //Fallback config path to framework's level config folder's config.ini
        //This is the framework's config
        $frameworkConfig = $root . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.ini';
        if (!file_exists($this->_iniFilePath)) {
        	if (file_exists($frameworkConfig)) {
            	$this->_iniFilePath = $frameworkConfig;
        	} else {
        		throw AiryException("No config file in {$frameworkConfig} error!!");
        	}   
        }
        
        
    }
    
    /**
     *  Use Singleton pattern here
     */
    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }    
        
        return self::$instance;
    }
	
    public function setIniFilePath($path) {
    	$this->_iniFilePath = $path;
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
         //Single database setting
         //Just one layer key-value structure
         if (!isset($parseIni[self::DB_INI_SYS_KEY])) {
         	 $result[0] = $dbArray;
         	 //for single database setting, we use db1 for key too
         	 $result[self::SINGLE_DB_SETTING_DB_ID] = $dbArray;
         	 return $result;
         }
         
         //For multiple database setting
         //Multiple layer structure
         foreach ($parseIni[self::DB_INI_SYS_KEY] as $mkey => $kv) {
             $tmpArray = array();
             foreach ($kv as $key => $value) {
                  $tmpArray[$key] = $value;
             }
             $result[] = $tmpArray;
             $result[$mkey] = $tmpArray;
         }

         return $result;
     }
     
//     public function getTimezone()
//     {
//     	 $iniArray = parse_ini_file ($this->_iniFilePath, true);
//         if (!isset($iniArray['Time_Zone']) || !isset($iniArray['Time_Zone']['timezone'])) {
//             return null;
//         }       
//         $tzArray = $iniArray['Time_Zone'];
//         
//         return $tzArray['timezone'];
//     }
     public function getAuthenticationConfig()
     {
     	 $iniArray = parse_ini_file ($this->_iniFilePath, true);
     	 if (!isset($iniArray['Authentication'])) {
     	 	 return null;
     	 }
     	 if (strtolower($iniArray['Authentication']['use_authentication']) == "true" ||
     	     strtolower($iniArray['Authentication']['use_authentication']) == "on") {
     	     strtolower($iniArray['Authentication']['use_authentication']) == "enable";	
     	 }
         if (strtolower($iniArray['Authentication']['use_authentication']) == "false" ||
     	     strtolower($iniArray['Authentication']['use_authentication']) == "off") {
     	     strtolower($iniArray['Authentication']['use_authentication']) == "disable";	
     	 }     	 
         return  $iniArray['Authentication'];
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
             return array('filename' => 'index.php');
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
              if ($configs[0] == self::CS_INI_SYS_KEY) {
                  $result[self::CSSKEY][] = $value;
              }
              if ($configs[0] == self::JS_INI_KEY) {
                  $result[self::JSKEY][] = $value;
              }                      
         }
    
         return $result;         
     }
     
     public function getErrorSetting() {
         $iniArray = parse_ini_file ($this->_iniFilePath, true);
     	 if (!isset($iniArray['Error'])) {
     	 	 return null;
     	 }     	
     	 return $iniArray['Error'];
     }
     
     public function getDisplayError()
     {
         $errorArray = $this->getErrorSetting();
         if (!isset($errorArray['display_error'])) {
         	return null;
         }
     	 
     	 if (strtolower($errorArray['display_error']) == "true" ||
     	     strtolower($errorArray['display_error']) == "on") {
     	     strtolower($errorArray['display_error']) == "enable";	
     	 }
         if (strtolower($errorArray['display_error']) == "false" ||
     	     strtolower($errorArray['display_error']) == "off") {
     	     strtolower($errorArray['display_error']) == "disable";	
     	 }     	 
         return  $errorArray['display_error'];
     }
     
//     public function getErrorForwarding()
//     {
//         $errorArray = $this->getErrorSetting();
//         if (!isset($errorArray['error_forwarding'])) {
//         	return null;
//         }
//     	 
//     	 if (strtolower($errorArray['error_forwarding']) == "true" ||
//     	     strtolower($errorArray['error_forwarding']) == "on") {
//     	     strtolower($errorArray['error_forwarding']) == "enable";	
//     	 }
//         if (strtolower($errorArray['error_forwarding']) == "false" ||
//     	     strtolower($errorArray['error_forwarding']) == "off") {
//     	     strtolower($errorArray['error_forwarding']) == "disable";	
//     	 }     	 
//         return  $errorArray['error_forwarding'];
//     }
     
     
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
     
     public function getCacheConfig()
     {
     	 $iniArray = parse_ini_file ($this->_iniFilePath, true);
         $cache = "data/cache";
         if (isset($iniArray['Cache'])) {
     	 	$cache = $iniArray['Cache'];
         }

         return $cache;
     }
     
     public function getCacheFolder()
     {
         $cacheArray = $this->getCacheConfig();
         if (isset($cacheArray['folder']) && !empty($cacheArray['folder'])) {
             return $cacheArray['folder'];
         }
         return 'data'.DIRECTORY_SEPARATOR.'cache';        
     }
}
?>
