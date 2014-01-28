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

class PdoMssqlComponent extends PdoSqlComponent {
	
	//mssql component is used for exporting limit and 
	protected $mssqlComponent;

    function __construct($databaseId = 0) {
    	parent::__construct($databaseId);
		$this->mssqlComponent = new MssqlComponent();
		$driver = isset($this->dbConfigArray['driver']) ? $this->dbConfigArray['driver'] : "dblib";
		$dsn = "{$driver}:host={$this->dbConfigArray['host']};dbname={$this->dbConfigArray['database']};charset={$this->dbConfigArray['encoding']}";
    	$this->pdoConn = new PDO($dsn, $this->dbConfigArray['id'], $this->dbConfigArray['pwd']);
    }
    
    public function limit($offset, $interval) {
		$this->mssqlComponent->limit($offset, $interval);
        $this->limitPart = $this->mssqlComponent->getLimitPart();
        return $this;
    }
    
    public function composeSelectStatement($selectPart, $joinPart, $joinOnPart, $wherePart, $groupPart, $orderPart, $limitPart) {
        return $this->mssqlComponent->composeSelectStatement($selectPart, $joinPart, $joinOnPart, $wherePart, $groupPart, $orderPart, $limitPart);
    } 
       

    function sqlEscape($content) {
        return $content;
    }    
}

?>
