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

    public function prepare($statement, array $driverOptions = array()) {
    	//return a prepareStatement here
    	return $this->_dbComponent->prepare($statement, $driverOptions);
    }
    
    public function beginTransaction() {
    	$this->_dbComponent->beginTransaction();
    	return $this;
    }
    
    public function rollBack() {
    	$this->pdoConn->rollBack();
    }
    
    public function commit() {
    	$this->_dbComponent->commit();
    	return $this;
    }
    
    public function exec($statement = null) {
    	$this->_dbComponent->exec($statement);
    	return $this;
    }
    
    public function setAttribute($attribute, $value) {
    	$this->_dbComponent->setAttribute($attribute, $value);
    	return $this;
    }
    
    public function getAttribute($attribute) {
    	return $this->_dbComponent->getAttribute($attribute);
    }
    
    public function errorCode() {
    	return $this->_dbComponent->errorCode();
    }
    
    public function errorInfo() {
    	return $this->_dbComponent->errorInfo();
    }
    
    public function getAvailableDrivers() {
    	return $this->_dbComponent->getAvailableDrivers();
    }
    
    public function inTransaction() {
    	return $this->_dbComponent->inTransaction();
    }
    
    public function lastInsertId($name = NULL) {
    	return $this->_dbComponent->lastInsertId($name = NULL);
    }
    
    public function query($statement, $fetchType = NULL, $fetch = NULL, array $ctorargs = NULL) {
    	return $this->_dbComponent->query($statement, $fetchType, $fetch, $ctorargs);
    }
    
    public function quote($str, $parameterType = PDO::PARAM_STR) {
    	return $this->_dbComponent->quote($str, $parameterType);
    }
    
    public function execute($statement = NULL, $fetchType = NULL, $fetch = NULL, array $ctorargs = NULL) {
    	return $this->_dbComponent->execute($statement, $fetchType, $fetch, $ctorargs);
    }
    
    public function setAutoClose($value) {
    	return $this->_dbComponent->setAutoClose($value);
    }
    
    public function closeConnection() {
    	return $this->_dbComponent->closeConnection();
    }

 
}

?>
