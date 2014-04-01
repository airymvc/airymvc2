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

class Logger {
	
	protected $file;
	private static $instance;
	
	const LEVEL_INFO 	= "INFO";
	const LEVEL_WARNING = "WARNING";
	const LEVEL_ERROR 	= "ERROR";
	
    function __construct($file = null) {
		$this->file = $file;
    }
	
    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new self($file = null);
        }    
        return self::$instance;
    } 
    
    public function setFile($file) {
    	$instance = self::getInstance($file);
    	return $instance;    
    }
    
	public function write($message, $level = self::LEVEL_INFO, $file = null) {
		$saveFile = is_null($file) ? $this->file : $file;
		$log = sprintf("[%s] %s", $level, $message);
		file_put_contents($saveFile, $log, FILE_APPEND);
	}
	
}