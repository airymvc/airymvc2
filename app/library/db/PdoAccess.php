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

class PdoAccess extends AbstractAccess implements DbAccessInterface  {
	
	//passing config here for unit test convenience
    function __construct($databaseId = 0, $iniFile = null) {
    	$config = Config::getInstance();
    	if (!is_null($iniFile)) {
        	$config->setIniFilePath($iniFile);
    	}
        $configArray = $config->getDBConfig();
        $this->dbConfigArray = $configArray[$databaseId];
        //initialize the object based on the database type
        $className = 'Pdo' . ucfirst(strtolower($this->dbConfigArray['dbtype'])) . 'Component';
        $this->_dbComponent = new $className($databaseId);
    }


 
}

?>
