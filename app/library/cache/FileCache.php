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

class FileCache {

    private $_cacheFolder;
    private static $instance;
    private $_lifetime;
    
    function __construct($cacheFolder = NULL) {
    	if (is_null($cacheFolder)) {
        	$config = Config::getInstance();
        	$root = PathService::getRootDir();
			$this->_cacheFolder = $root . DIRECTORY_SEPARATOR . $config->getCacheFolder();
    	} else {
    		$this->_cacheFolder = $cacheFolder;
    	}
		$this->_lifetime = 60*5;
    }
    
    public static function getInstance($cacheFolder = NULL)
    {
        if(is_null(self::$instance)) {
            self::$instance = new self($cacheFolder);
        }    
        
        return self::$instance;
    }    
    
    /**
     * Static method for user
     */
    public static function save($key, $content){
    	$instance = self::getInstance();
    	$key = md5($key);
        return $instance->saveFileData($key, $content);
    }
    
    public static function get($key){
    	$instance = self::getInstance();
     	$key = md5($key);
     	$filename = $instance->_cacheFolder . DIRECTORY_SEPARATOR .$key; 
     	$cache = null;  
     	if (file_exists($filename)) {	
     		if ((time() - filemtime($filename)) < ($instance->_lifetime)) {
     			$cache = $instance->getFileData($key);
     		} else {
     			$instance->removeFileData($key);
     		}
     	}
        return $cache;
    }
    
    
    public static function saveFile($key, $content){
    	$instance = self::getInstance();
        return $instance->saveFileData($key, $content);
    }
    
    public static function getFile($key){
    	$instance = self::getInstance();
        return $instance->getFileData($key);
    }
    
    public static function removeFile($key){
    	$instance = self::getInstance();
        return $instance->removeFileData($key);
    }
    
    public static function setLifeTime($time){
    	$instance = self::getInstance();
        return $instance->setLifeTimeData($time);
    }
    
    public static function getLifeTime(){
    	$instance = self::getInstance();
        return $instance->getLifeTimeData();
    }
    
    /**
     * save the data into a cache file
     * @return boolean 
     */
    public function saveFileData($filename, $content){
		$filename = $this->_cacheFolder . DIRECTORY_SEPARATOR .$filename;
		file_put_contents($filename, $content);
    }
    
    /**
     * get the data from a file
     * @return string 
     */
    public function getFileData($filename){
		$filename = $this->_cacheFolder . DIRECTORY_SEPARATOR .$filename;
		$content = null;
		if (file_exists($filename)) {
			$content = file_get_contents($filename);
		}
		return $content;
    }
    
    /**
     * remove the file
     */
    public function removeFileData($filename){
		$filename = $this->_cacheFolder . DIRECTORY_SEPARATOR .$filename;
		$command = "rm -rf {$filename}";
		if (file_exists($filename)) {
			exec($command);
		}
    }
    
    /**
     * set cache lifetime
     */
    public function setLifeTimeData($time){
		$this->_lifetime = $time;
    }
    
    /**
     * get cache lifetime
     */
    public function getLifeTimeData(){
		return $this->_lifetime;
    }
    
}

?>
