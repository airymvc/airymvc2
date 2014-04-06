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

abstract class SqlComponent {


    protected $dbConfigArray;
    protected $queryStmt;
    protected $selectStatement;
    protected $selectPart;
    protected $updatePart;
    protected $deletePart;
    protected $insertPart;
    protected $joinOnParts = array();    
    protected $wherePart;
    protected $orderPart;
    protected $groupPart;
    protected $limitPart;
    protected $keywords;
    protected $queryType;
    
    //joinPart and JoinOnPart will be modified after the method is deprecated
    protected $joinPart;
    protected $joinOnPart;
    
    protected $openIdentifier  = "";
    protected $closeIdentifier = "";

    function __construct($databaseId = 0) {
        $config = Config::getInstance();
        $configArray = $config->getDBConfig();
        $this->dbConfigArray = $configArray[$databaseId];
        $this->setKeywords();
        
        if (strtolower($this->dbConfigArray['dbtype']) == 'mysql') {
        	$this->setOpenIdentifier("`");
        	$this->setCloseIdentifier("`");
        }
        
        if (strtolower($this->dbConfigArray['dbtype']) == 'mssql') {
        	$this->setOpenIdentifier("[");
        	$this->setCloseIdentifier("]");
        }
    }

    /*
     * array (op of 'AND' or 'OR', array (op of 'like' or '=', array of (column => value)))
     * EX: array("AND"=>array("="=>array(field1=>value1, field2=>value2), ">"=>array(field3=>value3)))
     *     array(""=>array("="=>array(field1=>value1)))
     * if operators is null, all operators are "AND"
     * 
     * if it is after a inner join, should use "table.field1=>value1"
     * 
     */

    public function where($condition) {

    	$this->wherePart = " WHERE ";
        if (is_array($condition)) {
        	$this->wherePart .= $this->composeWhereByArray($condition);
        } else {
        	$this->wherePart .= $this->composeWhereByString($condition);
        }
        return $this;

    }
    
    protected function composeWhereByString($condition) {
    	$condition = $this->sqlEscape($condition);
    	return "({$condition})";
    }
    
    protected function composeWhereByArray($condition) {
    	$wherePart = "";
        $ops = array_keys($condition);
        if (empty($ops[0])) {
            //NO "AND", "OR" 
            $keys = array_keys($condition[$ops[0]]);
            $opr = $keys[0];
            $fieldArray = $condition[$ops[0]][$opr];
            $sub_keys = array_keys($fieldArray);
            
            $wherePart = $this->attachWhere($wherePart, $sub_keys[0], $fieldArray, $opr);
            
        } else {   
        	//Multiple Join Conditions
            $firstOne = TRUE;
            foreach ($ops as $index => $op) {
                foreach ($condition[$op] as $mopr => $fv_pair) {
                    if (is_array($fv_pair)) {
                        $mkeys = array_keys($fv_pair);
                        foreach ($mkeys as $idx => $mfield) {
                            if ($firstOne) {
                            	$oprator = null;
                                $firstOne = FALSE;
                            } else {
                            	$oprator = $op;
                            }
                            $wherePart = $this->attachWhere($wherePart, $mfield, $fv_pair, $mopr, $oprator);
                        }
                    } else {
                        //@TODO: to consider if the error log is necessary here
                        //log the error
                        $message = "JOIN condition uses array but not a correct array";
                        throw new AiryException($message, 0);
                    }
                }
            }
        }
        return $wherePart;    	
    }
    
    
    protected function attachWhere($whereString, $fieldKey, $fieldArray, $relationalOperator, $operator = null) {
        $pos = strpos($fieldKey, '.');
        $operator = is_null($operator) ? "" : strtoupper($operator);
        $key = "{$this->openIdentifier}{$fieldKey}{$this->closeIdentifier}";
        if ($pos != false){
            $tf = explode (".", $fieldKey);
            $key = "{$this->openIdentifier}{$tf[0]}{$this->closeIdentifier}.{$this->openIdentifier}{$tf[1]}{$this->closeIdentifier}";
        }
        $whereString .= "{$operator} {$key} {$relationalOperator} '{$fieldArray[$fieldKey]}' ";
        return $whereString;    	
    }
    
    
    public function andWhere($opString) {
    	$opString = $this->sqlEscape($opString);
    	$opString = " AND ({$opString})";
    	$this->wherePart .= $opString; 
    	return $this;  	
    }
    
    public function orWhere($opString) {
    	$opString = $this->sqlEscape($opString);
    	$opString = " OR ({$opString})";
    	$this->wherePart .= $opString; 
    	return $this;    	
    }

    public function inWhere($in) {
    	$opString = " IN ({$in})";
    	$this->wherePart .= $opString; 
    	return $this;    	
    }

    /**
     * EX: innerJoinOn ("tableName", "tableName.Key = to_be_Join_tableName.Key") 
     * 
     * @param string $table
     * @param string $condition
     */
    public function innerJoinOn($table, $condition) {

		$joinOn = "INNER JOIN {$this->openIdentifier}{$table}{$this->closeIdentifier} ON {$condition}";
		$this->joinOnParts[] = $joinOn;
        return $this;
    }
    
    public function getJoinOn() {
    	$joinOnString = "";
    	foreach ($this->joinOnParts as $i => $joinOn) {
    		$joinOnString = $joinOnString . " " . $joinOn;
    	}
    	return $joinOnString;
    }


    public function select($columns, $table, $distinct = null) {
        $this->queryType = "SELECT";
        if (is_null($distinct)) {
            $selectString = 'SELECT ';
        } else {
            $selectString = 'SELECT DISTINCT ';         
        }
        
        if (is_array($columns)) {
        	$this->selectPart = $this->composeSelectByArray($selectString, $columns, $table);
        } else {
        	$this->selectPart = $this->composeSelectByString($selectString, $columns, $table);
        }
        
        return $this;
    }
    
    protected function composeSelectByArray($selectString, $columns, $table) {
    	$selectPart = $selectString;
        foreach ($columns as $index => $col) {
            if ($index == count($columns) - 1) {
                $selectPart .= $col . " FROM {$this->openIdentifier}" . $table . "{$this->closeIdentifier}";
            } else {
                $selectPart .= $col . ", ";
            }
        }  
        return $selectPart;  	
    }
    
    protected function composeSelectByString($selectString, $columnString, $table) {
    	$selectPart = $selectString . $columnString ." FROM {$this->openIdentifier}" . $table . "{$this->closeIdentifier}";
    	return $selectPart;
    }

    /*
     * $table @string : the name of the table
     * $columns @array : the columns array(column_name => column_value, column_name1 => column_value1)
     */

    public function update($columns, $table) {
        $this->queryType = "UPDATE";
        $this->updatePart = "UPDATE {$this->openIdentifier}" . $table . "{$this->closeIdentifier} SET ";
        $size = count($columns) - 1;
        $n = 0;
        foreach ($columns as $column_index => $column_value) {
        	$lastAppend = "', ";
            if ($n == $size) {
                $lastAppend = "'";
            }
            $this->updatePart .= "{$this->openIdentifier}" . $column_index . "{$this->closeIdentifier}='" . $column_value . $lastAppend;
            $n++;
        }

        return $this;
    }

    /*
     * $table @string : the name of the table
     * $columns @array : the columns array(column_name => column_value, column_name1 => column_value1)
     * 
     * $keywords like TIMESTAMP, it needs to be taken care of 
     */

    public function insert($columns, $table) {
        $this->queryType = "INSERT";
        $this->insertPart = "INSERT INTO " . $table . " ( ";
        $size = count($columns) - 1;
        $n = 0;
        foreach ($columns as $columnIndex => $columnValue) {
        	$attach = "{$this->closeIdentifier}, ";
            if ($n == $size) {
            	$attach = "{$this->closeIdentifier}) VALUES (";
            }
            $this->insertPart = $this->insertPart . "{$this->openIdentifier}" . $columnIndex . $attach;
            $n++;
        }

        $n = 0;
        foreach ($columns as $columnIndex => $columnValue) {
        	$middle = "'";
            $last = "', ";
            if ($n == $size) {
            	$middle = "'";
            	$last = "')";
            }
            if(is_bool($columnValue)) {
            	$columnValue = ($$columnValue) ? 1 : 0;
            }
            if (array_key_exists($columnValue, $this->keywords)) {
            	$middle = "";
            	$last = "";
            }
            $this->insertPart = $this->insertPart . $middle . $columnValue . $last;
            $n++;
        }

        return $this;
    }

    /*
     * $table @string : the name of the table
     */

    public function delete($table) {
        $table = $this->sqlEscape($table);
        $this->queryType = "DELETE";
        $this->deletePart = "DELETE FROM " . $table;
        return $this;
    }

    /*
     *  $offset @int
     *  $interval @int
     */

    public function limit($offset, $interval) {

    	$this->limitPart = "";    	
    	if (is_null($offset) && is_null($interval)) {
    		return $this;
    	}

        $offset = (!is_null($offset)) ? $offset : 0;

        $insert = "";
        if (!is_null($offset)) {
        	$insert = trim($offset);         
        }
        $this->limitPart = " LIMIT " . $insert . ", " . trim($interval);
        return $this;
    }

    /*
     *  $column @string: column name in the database
     *  $if_desc @int: null or 1
     */

    public function orderBy($column, $ifDesc = NULL) {
    	$this->orderPart = "";
        $desc = "";
        if ($ifDesc != NULL) {
        	$desc = " DESC";
        }
        $this->orderPart .= " ORDER BY " . $column . $desc;
        return $this;
    }
    
    /*
     *  $column @string: column name in the database
     */
    public function groupBy($column) {
    	$this->groupPart = "";
        $this->groupPart = " GROUP BY " . $column;
        return $this;
    }
    
    
    public function execute() {}

    /**
     * @return the $dbConfigArray
     */
    public function getdbConfigArray() {
        return $this->dbConfigArray;
    }

    /**
     * @return the $queryStmt
     */
    public function getStatement() {
        //Combine every part of the query statement
        switch ($this->queryType) {
            case "SELECT":
                $this->queryStmt = null;
				$this->queryStmt = $this->composeSelectStatement($this->selectPart, $this->getJoinOn(), $this->joinPart, $this->joinOnPart, $this->wherePart, 
									 							 $this->groupPart, $this->orderPart, $this->limitPart);
                break;
            case "UPDATE":
                $this->queryStmt = null;
                $this->queryStmt = $this->updatePart . $this->wherePart;
                break;
            case "INSERT":
                $this->queryStmt = null;
                $this->queryStmt = $this->insertPart;
                break;
            case "DELETE":
                $this->queryStmt = null;
                $this->queryStmt = $this->deletePart . $this->wherePart;
                break;
        }
        return $this->queryStmt;
    }
    
    /**
     * if the query type is select, this function is to compose the statement
     * 
     * @param string $selectPart
     * @param string $joinPart
     * @param string $joinOnPart
     * @param string $wherePart
     * @param string $groupPart
     * @param string $orderPart
     * @param string $limitPart
     */
    public function composeSelectStatement($selectPart, $joinOnParts, $joinPart, $joinOnPart, $wherePart, $groupPart, $orderPart, $limitPart) {
        $queryStmt = $selectPart . $joinOnParts . $joinPart . $joinOnPart . $wherePart . $groupPart . $orderPart . $limitPart;
        return $queryStmt;
    }    
 
    /**
     * Deprecated method 
     */
    
    public function getSelectStatement(){
        if ($this->queryType != "SELECT") {
            return null;
        }
        $this->selectStatement = null;
        $this->selectStatement = $this->selectPart . $this->getJoinOn() . $this->joinPart . $this->joinOnPart
                           . $this->wherePart . $this->groupPart . $this->orderPart . $this->limitPart;         
        return $this->selectStatement;
    }
    
    public function cleanAll(){
        $this->queryType  = "";
        $this->selectPart = "";
        $this->joinOnParts = array();
        $this->joinPart   = "";
        $this->joinOnPart = "";
        $this->wherePart  = "";
        $this->orderPart  = "";
        $this->limitPart  = "";
        $this->updatePart = "";
        $this->insertPart = "";
        $this->deletePart = "";
        $this->groupPart  = "";
    }

    /**
     * @param field_type $dbConfigArray
     */
    public function setdbConfigArray($dbConfigArray) {
        $this->dbConfigArray = $dbConfigArray;
    }

    /**
     * @param field_type $queryStmt
     */
    public function setStatement($queryStmt) {
        $this->queryStmt = $queryStmt;
    }

    public function setKeywords() {
        $this->keywords['CURRENT_TIMESTAMP'] = "CURRENT_TIMESTAMP";
    }

    function sqlEscape($content) {}
    
    //The following getter is for unit tests
    
	/**
	 * @return the $selectPart
	 */
	public function getSelectPart() {
		return $this->selectPart;
	}

	/**
	 * @return the $updatePart
	 */
	public function getUpdatePart() {
		return $this->updatePart;
	}

	/**
	 * @return the $deletePart
	 */
	public function getDeletePart() {
		return $this->deletePart;
	}

	/**
	 * @return the $insertPart
	 */
	public function getInsertPart() {
		return $this->insertPart;
	}

	/**
	 * @return the $joinPart
	 */
	public function getJoinPart() {
		return $this->joinPart;
	}

	/**
	 * @return the $joinOnPart
	 */
	public function getJoinOnPart() {
		return $this->joinOnPart;
	}

	/**
	 * @return the $wherePart
	 */
	public function getWherePart() {
		return $this->wherePart;
	}

	/**
	 * @return the $orderPart
	 */
	public function getOrderPart() {
		return $this->orderPart;
	}

	/**
	 * @return the $groupPart
	 */
	public function getGroupPart() {
		return $this->groupPart;
	}

	/**
	 * @return the $limitPart
	 */
	public function getLimitPart() {
		return $this->limitPart;
	}
	/**
	 * @return the $closeIdentifier
	 */
	public function getCloseIdentifier() {
		return $this->closeIdentifier;
	}

	/**
	 * @param field_type $closeIdentifier
	 */
	public function setCloseIdentifier($identifier) {
		$this->closeIdentifier = $identifier;
	}
	/**
	 * @return the $openIdentifier
	 */
	public function getOpenIdentifier() {
		return $this->openIdentifier;
	}

	/**
	 * @param field_type $openIdentifier
	 */
	public function setOpenIdentifier($identifier) {
		$this->openIdentifier = $identifier;
	}
	

}

?>
