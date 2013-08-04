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
    
    function __construct() {
        $config = Config::getInstance();
        $root = PathService::getRootDir();
		$this->_cacheFolder = $root . DIRECTORY_SEPARATOR . $config->getCacheFolder();
		$this->_lifetime = 60*10; //seconds
    }
    
    
    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
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
     	$filename = $this->_cacheFolder . DIRECTORY_SEPARATOR .$filename; 
     	$cache = null;  	
     	if (filemtime($filename) > (time() - 60*10)) {
     		$instance->getFileData($key);
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
    
}

?>
