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

class PdoMysqlComponent extends PdoSqlComponent {
	
    function __construct() {

    	$this->setIdentifier();
    }
    
    public function configConnection($dbConfigArray) {
    	$hostArray = explode(":", $dbConfigArray['host']);
    	$this->host = $hostArray[0];
    	$this->port = isset($hostArray[1]) ? $hostArray[1] : "3306";
    	$charset = isset($dbConfigArray['encoding']) ? "charset={$dbConfigArray['encoding']}" : "charset=utf8";
    	
    	$this->dsn = "{$dbConfigArray['dbtype']}:host={$this->host};port={$this->port};dbname={$dbConfigArray['database']};{$charset}";
    	$this->setConnection($this->dsn, $dbConfigArray['id'], $dbConfigArray['pwd']);
    }

    function sqlEscape($content) {
        return $content;
    }    
}

?>
