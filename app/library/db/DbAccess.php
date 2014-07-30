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

class DbAccess extends AbstractAccess implements DbAccessInterface {
	    
    public function config($databaseId = 0, $iniFile = null) {
    	$config = Config::getInstance();
    	if (!is_null($iniFile)) {
    		$config->setIniFilePath($iniFile);
    	}
    	$configArray = $config->getDBConfig();
    	$this->setDbConfig($configArray[$databaseId]);
    	$this->setComponent($configArray[$databaseId]);
    }
    
    public function setDbConfig($config) {
    	$this->dbConfigArray = $config;
    }
    
    public function setComponent($config) {
    	//initialize the object based on the database type
    	$className = ucfirst(strtolower($config['dbtype'])) . 'Component';
    	if (strtolower($config['dbtype']) == "mysql") {
    		$className = ucfirst(strtolower($config['connection_type'])) . 'Component';
    	}
    	
    	$this->_dbComponent = new $className();
    }

}

?>
