<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
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

}

?>