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
	
	protected $pdoConn;
	protected $host;
	protected $port = 3306;
	//mssql component is used for exporting limit and 
	protected $mssqlComponent;

    function __construct($databaseId = 0) {
		parent::__construct($databaseId);
		
		$hostArray = explode(":", $this->dbConfigArray['host']);	
		$this->host = $hostArray[0];
		$this->port = $hostArray[1]; 
		$dsn = "{$this->dbConfigArray['dbtype']}:host={$this->host};port={$this->port};dbname={$this->dbConfigArray['database']};charset={$this->dbConfigArray['encoding']}";

    	$this->pdoConn = new PDO($dsn, $this->dbConfigArray['id'], $this->dbConfigArray['pwd']);
    	$this->mssqlComponent = new MssqlComponent();		
    }
    
    public function limit($offset, $interval) {
		$this->mssqlComponent->limit($offset, $interval);
        $this->limitPart = $this->mssqlComponent->getLimitPart();
        return $this;
    }
    
    public function composeSelectStatement($selectPart, $joinPart, $joinOnPart, $wherePart, $groupPart, $orderPart, $limitPart) {
        return $this->mssqlComponent->composeSelectStatement($selectPart, $joinPart, $joinOnPart, $wherePart, $groupPart, $orderPart, $limitPart);
    } 
    
    
    public function execute($statement = NULL) {

    	$statement = is_null($statement) ? $this->getStatement() : $statement;
        $con = mssql_connect($this->dbConfigArray['host'], $this->dbConfigArray['id'], $this->dbConfigArray['pwd']);
        mssql_set_charset($this->dbConfigArray['encoding'] ,$con);
          
        if (!$con) {
            die('Could not connect: ' . mssql_error());
        }

        mssql_select_db($this->dbConfigArray['database'], $con);
        $mssql_results = mssql_query($statement);

        if (!$mssql_results) {
            die('Could not query:' . mssql_error());
        }
        mssql_close($con);
        $this->cleanAll();
        
        return $mssql_results;
    }

    function sqlEscape($content) {
        return $content;
    }    
}

?>
