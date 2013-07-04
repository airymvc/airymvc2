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

/**
 * Description of pathService
 *
 * @author Hung-Fu Aaron Chang
 */
class PathService {
    //put your code here
    private static $instance;
    
    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }    
        
        return self::$instance;
    }
    
    
    public function getAbsoluteHostURL() {
          $url = 'http://' . $this->getAbsoluteHostPath();
          return $url;
    }
    
    public function getAbsoluteHostPath() {
          $serverName = $_SERVER['SERVER_NAME'];
          $serverPort = $_SERVER['SERVER_PORT'];
          $indexRelativePath = $_SERVER['SCRIPT_NAME'];
          $config = Config::getInstance();
          $leadFileName = $config->getLeadFileName();
          $replace = "/" . $leadFileName;
          $vfolder = str_replace($replace, '', $indexRelativePath);
          $serverHost = $serverName. ":" . $serverPort . $vfolder;
          return $serverHost;
    }
    public function getFormActionURL($moduleName, $controllerName, $actionName, $params = null) {
                $config = Config::getInstance();
                $mkey = $config->getModuleKeyword();
                $ckey = $config->getControllerKeyword();
                $akey = $config->getActionKeyword();
                $leadFileName = $config->getLeadFileName();
                $url = $this->getAbsoluteHostURL()."/{$leadFileName}?{$mkey}={$moduleName}&{$ckey}={$controllerName}&{$akey}={$actionName}";
                if ($params == null){ 
                    return $url;
                }
                
                foreach ($params as $key => $value) {
                    $url = $url . "&{$key}={$value}";
                }
                return $url;
    }   
    
    public function getRootDir()
    {
        $dir = dirname(dirname(__FILE__));
        return $dir;
    }
    
    public function getProjectDir()
    {
        $rootDir = $this->getRootDir();
        $projDir = $rootDir . DIRECTORY_SEPARATOR . "project";
        return $projDir;
                      
    }
    
    public function getModulesDir()
    {
        $projDir = $this->getProjectDir();
        $modulesDir = $projDir . DIRECTORY_SEPARATOR . "modules";
        return $modulesDir;
                      
    }

}

?>