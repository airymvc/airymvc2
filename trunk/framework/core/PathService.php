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
    /**
     * Static method
     */
    public static function getAbsoluteHostURL() {
    	$instance = self::getInstance();
    	return $instance->getAbsoluteHostURLData();
    }

    public static function getAbsoluteHostPath() {
    	$instance = self::getInstance();
    	return $instance->getAbsoluteHostPathData();
    }    
    
    public static function getFormActionURL($moduleName, $controllerName, $actionName, $params = null, $isDirective = False) {
    	$instance = self::getInstance();
    	return $instance->getFormActionURLData($moduleName, $controllerName, $actionName, $params, $isDirective);
    }    
        
    public static function getRootDir() {
    	$instance = self::getInstance();
    	return $instance->getRootDirData();
    }     
    
    public static function getProjectDir() {
    	$instance = self::getInstance();
    	return $instance->getProjectDirData();
    }
    
    public static function getModulesDir() {
    	$instance = self::getInstance();
    	return $instance->getModulesDirData();
    }    
    

    /*
     * Object methods
     */
    private function getAbsoluteHostURLData() {
          $url = 'http://' . $this->getAbsoluteHostPath();
          return $url;
    }
    
    private function getAbsoluteHostPathData() {
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
    private function getFormActionURLData($moduleName, $controllerName, $actionName, $params = null, $isDirective = False) {
                $config = Config::getInstance();
                $mkey = $config->getModuleKeyword();
                $ckey = $config->getControllerKeyword();
                $akey = $config->getActionKeyword();
                $leadFileName = $config->getLeadFileName();
                
                $queryOp = "?";
                if ($isDirective) {
                	$url = $this->getAbsoluteHostURL()."/{$moduleName}/{$controllerName}/{$actionName}";
                } else {
                	$queryOp = "&";
                	$url = $this->getAbsoluteHostURL()."/{$leadFileName}?{$mkey}={$moduleName}&{$ckey}={$controllerName}&{$akey}={$actionName}";
                }
                
                if ($params == null){ 
                    return $url;
                }

                $queryStr = "";
                foreach ($params as $key => $value) {
                	if ($queryStr == "") {
                		$queryStr = "{$key}={$value}";
                	} else {
                		$queryStr .= "&{$key}={$value}";
                	}
                }    
                $url .= "{$queryOp}{$queryStr}";
                
                return $url;
    }   
    
    private function getRootDirData()
    {
        $dir = dirname(dirname(__FILE__));
        return $dir;
    }
    
    private function getProjectDirData()
    {
        $rootDir = $this->getRootDir();
        $projDir = $rootDir . DIRECTORY_SEPARATOR . "project";
        return $projDir;
                      
    }
    
    private function getModulesDirData()
    {
        $projDir = $this->getProjectDir();
        $modulesDir = $projDir . DIRECTORY_SEPARATOR . "modules";
        return $modulesDir;
                      
    }
    
//    private function composeActionViewData($moduleName, $controllerName, $actionName) {
//            $actionViewClassName = ucwords($actionName) . self::VIEW_POSTFIX;
//            $actionViewFile = "project". DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$moduleName .DIRECTORY_SEPARATOR. "views".DIRECTORY_SEPARATOR .$controllerName. DIRECTORY_SEPARATOR. $actionViewClassName .".php";
//            $absActionViewFile = PathService::getInstance()->getRootDir() . DIRECTORY_SEPARATOR . $actionViewFile;
//        
//            if (!file_exists($absActionViewFile)) {
//                $name = $controllerName . "_" . ucwords($actionName);
//                $actionViewClassName = $name . self::VIEW_POSTFIX;
//                $actionViewFile = "project". DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$moduleName .DIRECTORY_SEPARATOR. "views".DIRECTORY_SEPARATOR . $actionViewClassName .".php";
//                $absActionViewFile = PathService::getInstance()->getRootDir() . DIRECTORY_SEPARATOR . $actionViewFile;
//            }
//            return array(0 => $actionViewClassName,
//            			 1 => $actionViewFile, 
//                         2 => $absActionViewFile);        	
//    }

}

?>