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

class DbConfig {

	/**
	 * This is the function to get the database access objects for all the database settings in the config.ini file.
	 * 
	 * @throws AiryException
	 */
    public static function getConfig() {
    	$config = Config::getInstance();
        $dbSettings = $config->getDBConfig();
        $dbAccessElements = array();

        foreach ($dbSettings as $idx => $configArray) {
        	if (!isset($configArray['dbtype'])) {
        		throw new AiryException("no dbtype setting in the config.ini");
        	}
        	//pdo is the default connection type
        	$connectionType = "pdo";
        	if (isset($configArray['connection_type'])) {
        		$connectionType = $configArray['connection_type'];
        	} else if (strtolower($configArray['dbtype']) == "mongodb") {
        		$connectionType = "mongodb";
        	}

            if (strtolower($connectionType) == "pdo") {
            	$dbAccessElements[$idx] = new PdoAccess($idx);
            } else if (strtolower($connectionType) == "mongodb") {
            	$dbAccessElements[$idx] = new MongoDbAccess($idx);
            } else {
            	$dbAccessElements[$idx] = new DbAccess($idx);
            }
        }
        return $dbAccessElements;
    }

}
?>
