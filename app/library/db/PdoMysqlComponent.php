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
	
	protected $pdoConn;
	protected $host;
	protected $port = 3306;

    function __construct($databaseId = 0) {
		parent::__construct($databaseId);
		
		$hostArray = explode(":", $this->dbConfigArray['host']);	
		$this->host = $hostArray[0];
		$this->port = $hostArray[1]; 
		$dsn = "{$this->dbConfigArray['dbtype']}:host={$this->host};port={$this->port};dbname={$this->dbConfigArray['database']};charset={$this->dbConfigArray['encoding']}";

    	$this->pdoConn = new PDO($dsn, $this->dbConfigArray['id'], $this->dbConfigArray['pwd']);		
    }

    function sqlEscape($content) {
        return $content;
    }    
}

?>
