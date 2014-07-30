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

    function __construct() {
    	$this->mssqlComponent = new MssqlComponent();
		$this->setIdentifier();
    }
    
    public function configConnection($dbConfigArray) {
		
		$driver = isset($dbConfigArray['driver']) ? $dbConfigArray['driver'] : "dblib";
		$charset = isset($dbConfigArray['encoding']) ? "charset={$dbConfigArray['encoding']}" : "charset=utf8";
		
		$this->dsn = "{$driver}:host={$dbConfigArray['host']};dbname={$dbConfigArray['database']};{$charset}";
		$this->setConnection($this->dsn, $dbConfigArray['id'], $dbConfigArray['pwd']);
    }
        
    
    public function limit($offset, $interval) {
		$this->mssqlComponent->limit($offset, $interval);
        $this->limitPart = $this->mssqlComponent->getLimitPart();
        return $this;
    }
    
    public function composeSelectStatement($selectPart, $joinOnParts, $joinPart, $joinOnPart, $wherePart, $groupPart, $orderPart, $limitPart) {
        return $this->mssqlComponent->composeSelectStatement($selectPart, $joinOnParts, $joinPart, $joinOnPart, $wherePart, $groupPart, $orderPart, $limitPart);
    } 
       

    function sqlEscape($content) {
        return $content;
    }    
}

?>
