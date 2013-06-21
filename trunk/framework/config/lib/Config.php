<?php

class Config{
    
    private static $instance;
    private $_iniFilePath;
    
    function __construct($iniFilePath = null)
    {
        $root = PathService::getInstance()->getRootDir();
        if (is_null($iniFilePath))
        {
            $this->_iniFilePath = $root . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.ini';
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
	
     public function getDBConfig()
     {
         $ini_array = parse_ini_file ($this->_iniFilePath, true);
         $db_array = $ini_array['DB'];
         
         return $db_array;
     }
     public function getTimezone()
     {
     	 $ini_array = parse_ini_file ($this->_iniFilePath, true);
         if (!isset($ini_array['Time_Zone']) || !isset($ini_array['Time_Zone']['timezone'])) {
             return null;
         }       
         $tz_array = $ini_array['Time_Zone'];
         
         return $tz_array['timezone'];
     }
     public function getAuthenticationConfig()
     {
     	 $ini_array = parse_ini_file ($this->_iniFilePath, true);
         $au_array = $ini_array['Authentication'];
         
         return $au_array;
     }
     public function getMVCKeyword()
     {
     	 $ini_array = parse_ini_file ($this->_iniFilePath, true);
         if(!isset($ini_array['MVC_Keyword']))
         {
            return null;
         }
         $mvc_array = $ini_array['MVC_Keyword'];
         return $mvc_array;
     }
     public function getDefaultModule()
     {
     	 $ini_array = parse_ini_file ($this->_iniFilePath, true);
         $modules = $ini_array['Module'];

         $default_module = $modules["default"];

         return $default_module;
     }
     
     public function getLanguage()
     {
     	 $ini_array = parse_ini_file ($this->_iniFilePath, true);
         $language = $ini_array['Language'];

         return $language;
     }
     public function getLeadFile()
     {
     	 $ini_array = parse_ini_file ($this->_iniFilePath, true);
         if (!isset($ini_array['Lead_File'])) {
             return null;
         }
         
         return $ini_array['Lead_File'];
     }
     public function getModuleKeyword()
     {
         $mvc_array = $this->getMVCKeyword();
         if (isset($mvc_array['module'])&& !is_null($mvc_array)) {
             return $mvc_array['module'];
         }
         return 'module';
     }
     
     public function getControllerKeyword()
     {
         $mvc_array = $this->getMVCKeyword();
         if (isset($mvc_array['controller']) && !is_null($mvc_array)) {
             return $mvc_array['controller'];
         }
         return 'controller';       
     }
     public function getActionKeyword()
     {
         $mvc_array = $this->getMVCKeyword();
         if (isset($mvc_array['action'])&& !is_null($mvc_array)) {
             return $mvc_array['action'];
         }
         return 'action';        
     }
     
     public function getDefaultLanguage()
     {
         $lang_array = $this->getLanguage();
         if (isset($lang_array['default'])) {
             return $lang_array['default'];
         }
         return 'en-US';        
     }
     
     public function getLanguageFolder()
     {
         $lang_array = $this->getLanguage();
         if (   isset($lang_array['plugin_folder']) 
             && !empty($lang_array['plugin_folder'])) {
             return $lang_array['plugin_folder'];
         }
         return 'lang';        
     }
     
     public function getLanguageKeyword()
     {
         $lang_array = $this->getLanguage();
         if (   isset($lang_array['keyword']  ) 
             && !empty($lang_array['keyword'])) {
             return $lang_array['keyword'];
         }
         return 'lang';        
     }
     public function getLeadFileName()
     {
         $lead_file_array = $this->getLeadFile();
         if (   isset($lead_file_array['filename']  ) 
             && !empty($lead_file_array['filename'])) {
             return $lead_file_array['filename'];
         }
         return 'index.php';        
     }
     
     public function getScriptPlugin()
     {
     	 $ini_array = parse_ini_file ($this->_iniFilePath, true);
         if (!isset($ini_array['JS_Plugin'])) {
             return null;
         }
    
         return $ini_array['JS_Plugin'];         
     }
     
}
?>
