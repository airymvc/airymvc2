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

abstract class AbstractAccess {
	
	protected $_dbComponent;

	
   	public function select($columns, $table, $distinct = 0) {
   		$this->_dbComponent->select($columns, $table, $distinct);
   		return $this;
   	}
   	
   	public function where($condition) {
   		$this->_dbComponent->where($condition);
   		return $this;
   	}
   	
    public function andWhere($opString) {
    	$this->_dbComponent->andWhere($opString);
   		return $this;
    }
    
    public function orWhere($opString) {
    	$this->_dbComponent->orWhere($opString);
   		return $this;
    }
    
    public function InWhere($in) {
    	$this->_dbComponent->inWhere($in);
   		return $this;
    }
    
    public function innerJoin($tables) {
    	$this->_dbComponent->innerJoin($tables);
   		return $this;
    }
    
    public function orJoinOn($condition) {
    	$this->_dbComponent->orJoinOn($condition);
   		return $this;    	
    }
    
    public function addJoinOn($condition) {
    	$this->_dbComponent->addJoinOn($condition);
   		return $this;    	
    }

    public function update($columns, $table) {
    	$this->_dbComponent->update($columns, $table);
   		return $this;    	
    }
    
    public function insert($columns, $table) {
    	$this->_dbComponent->insert($columns, $table);
   		return $this;    	
    }
    
    public function delete($table) {
    	$this->_dbComponent->delete($table);
   		return $this;    	
    }
    
    public function execute() {
    	return $this->_dbComponent->execute();   	
    }
    
    public function getStatement() {
    	return $this->_dbComponent->getStatement();
    }
    
    public function groupBy($column) {
    	$this->_dbComponent->groupBy($column);
   		return $this;    	
    }
    
    public function joinOn($condition) {
    	$this->_dbComponent->joinOn($condition);
   		return $this;
    }
    
    public function limit($offset, $interval) {
    	$this->_dbComponent->limit($offset, $interval);
   		return $this;
    }

    public function orderBy($column, $ifDesc = NULL) {
    	$this->_dbComponent->aorderBy($column, $ifDesc);
   		return $this;
    }
    
    public function getDbComponent() {
    	return $this->_dbComponent;
    }
}

?>
