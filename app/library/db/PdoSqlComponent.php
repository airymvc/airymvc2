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

class PdoSqlComponent extends SqlComponent {
	
	protected $pdoConn;
	protected $dsn;
	protected $host;
	protected $port;

    function __construct($databaseId = 0) {
		parent::__construct($databaseId);
		// in order to fit pdo's prepare statement, take out the identifiers
		// ex: :field1
    	$this->setOpenIdentifier("");
        $this->setCloseIdentifier("");	
    }
    
    public function beginTransaction() {
    	return $this->pdoConn->beginTransaction();
    }
    
    public function prepare($statement, array $driverOptions = array()) {
    	//return a prepareStatement here
    	return $this->pdoConn->prepare($statement, $driverOptions);
    }
    
    public function rollBack() {
    	$this->pdoConn->rollBack();
    }
    
    public function commit() {
    	return $this->pdoConn->commit();
    }
    
    public function exec($statement = null) {
    	$statement = is_null($statement) ? $this->getStatement() : $statement;
    	return $this->pdoConn->exec($statement);
    }
    
    public function setAttribute($attribute, $value) {
    	$this->pdoConn->setAttribute($attribute, $value);
    }

    public function getAttribute($attribute) {
    	return $this->pdoConn->getAttribute($attribute);
    }
    
    public function errorCode() {
    	return $this->pdoConn->errorCode();
    }
    
    public function errorInfo() {
    	return $this->pdoConn->errorInfo();
    }
    
    public function getAvailableDrivers() {
    	return $this->pdoConn->getAvailableDrivers();
    }
    
    public function inTransaction() {
    	return $this->pdoConn->inTransaction();
    }
    
    public function lastInsertId($name = NULL) {
    	return $this->pdoConn->lastInsertId($name = NULL);
    }
    
    public function query($statement, $fetchType = NULL, $fetch = NULL, array $ctorargs = NULL) {
    	if (!is_null($fetchType)) {
    		if (is_int($fetch)) {
    			return $results = $this->pdoConn->query($statement, $fetchType, $fetch);
    		}
    		if (is_string($fetch)) {
    			return $results = $this->pdoConn->query($statement, $fetchType, $fetch, $ctorargs);
    		}
    		if (is_object($fetch)) {
    			return $results = $this->pdoConn->query($statement, $fetchType, $fetch);
    		}
    	}
    	return $results = $this->pdoConn->query($statement);
    }
    
    public function quote($str, $parameterType = PDO::PARAM_STR) {
    	return $this->pdoConn->quote($str, $parameterType);
    }
        
    public function execute($statement = NULL, $fetchType = NULL, $fetch = NULL, array $ctorargs = NULL) {

    	$statement = is_null($statement) ? $this->getStatement() : $statement;
		try {
			$results = $this->query($statement, $fetchType, $fetch, $ctorargs);
		} catch(PDOException $e) {
    		 echo 'PDO ERROR: ' . $e->getMessage();
		}
		//close the connection
		$this->pdoConn = null;
        $this->cleanAll();
        
        return $results;
    }

    function sqlEscape($content) {

        //check if $content is an array
        if (is_array($content)) {
            foreach ($content as $key => $value) {
                $content[$key] = $this->quote($value);
            }
        } else {
            //check if $content is not an array
            $content = $this->quote($content);
        }

        return $content;
    }    
}

?>
