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
	
	//passing config here for unit test convenience
    function __construct($databaseId = 0, $config = null) {
    	if (is_null($config)) {
        	$config = Config::getInstance();
    	}
        $configArray = $config->getDBConfig();
        $this->dbConfigArray = $configArray[$databaseId]; 
        $className = ucfirst(strtolower($this->dbConfigArray['dbtype'])) . 'Component';
        
        //If the database type is mysql, determining whether mysql or mysqli to be initialized
        if ($className == "Mysql") {
        	$className = ucfirst(strtolower($this->dbConfigArray['connection_type'])) . 'Component';
        	$this->_dbComponent = new $className($databaseId);
        }
    }

}

?>
