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

class MysqlAccess implements DbAccessInterface{

    private $dbConfigArray;
    private $queryStmt;
    private $selectStatement;
    private $selectPart;
    private $updatePart;
    private $deletePart;
    private $insertPart;
    private $joinPart;
    private $joinOnPart;
    private $wherePart;
    private $orderPart;
    private $groupPart;
    private $limitPart;
    private $keywords;
    private $queryType;

    function __construct($databaseId = 0) {
        $config = Config::getInstance();
        $configArray = $config->getDBConfig();
        $this->dbConfigArray = $configArray[$databaseId];
        $this->setKeywords();
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
        $ops = array_keys($condition);
        if (empty($ops[0])) {
            //NO "AND", "OR" 
            $keys = array_keys($condition[$ops[0]]);
            $opr = $keys[0];
            $field_array = $condition[$ops[0]][$opr];
            $sub_keys = array_keys($field_array);
            $pos = strpos($sub_keys[0], '.');
            if ($pos == false){
                $this->wherePart = $this->wherePart
                     . " `" . $sub_keys[0] . "` " . $opr . " '" . $condition[$ops[0]][$opr][$sub_keys[0]] . "' ";
            } else {
                $tf = explode (".", $sub_keys[0]);
                $this->wherePart = $this->wherePart
                     . " `{$tf[0]}`.`{$tf[1]}` " . $opr . " '" . $condition[$ops[0]][$opr][$sub_keys[0]] . "' ";
            }
        } else {   //Multiple Join Conditions
            $first_one = TRUE;
            foreach ($ops as $index => $op) {
                foreach ($condition[$op] as $mopr => $fv_pair) {
                    if (is_array($fv_pair)) {
                        $mkeys = array_keys($fv_pair);
                        foreach ($mkeys as $idx => $mfield) {
                            if ($first_one) {
                                $pos = strpos($mfield, '.');
                                if ($pos == false){
                                    $this->wherePart = $this->wherePart
                                           . " `" . $mfield . "` " . $mopr . " '" . $fv_pair[$mfield] . "' ";
                                } else {
                                    $tf = explode (".", $mfield);
                                    $this->wherePart = $this->wherePart
                                           . " `{$tf[0]}`.`{$tf[1]}` " . $mopr . " '" . $fv_pair[$mfield] . "' ";                               
                                }
                                $first_one = FALSE;
                            } else {
                                $pos = strpos($mfield, '.');
                                if ($pos == false){
                                    $this->wherePart = $this->wherePart . strtoupper($op)
                                           . " `" . $mfield . "` " . $mopr . " '" . $fv_pair[$mfield] . "' ";
                                } else {
                                    $tf = explode (".", $mfield);
                                    $this->wherePart = $this->wherePart . strtoupper($op)
                                           . " `{$tf[0]}`.`{$tf[1]}` " . $mopr . " '" . $fv_pair[$mfield] . "' ";                              
                                }
                            
                            }
                        }
                    } else {
                        //@TODO: to consider if the error log is necessary here
                        //log the error
                        $message = "JOIN condition is not an array";
                        error_log($message, 0);
                    }
                }
            }
        }
    }

    public function innerJoin($tables) {
        //INNER JOIN messages INNER JOIN languages
        $tables = $this->mysql_escape($tables);

        foreach ($tables as $index => $tbl) {
            if ($index == 0) {
                $this->joinPart = " INNER JOIN `" . $tbl . "`";
            } else {
                $this->joinPart = $this->joinPart . " INNER JOIN `" . $tbl . "`";
            }
        }
    }

    /*
     * conditions represent 
     * Ex: array ("" => array(array("=", table1=>field1, table2=>field2)))
     *     array ("AND" => array(array("=", table1=>field1, table2=>field2), array("<>", table3=>field3, table2=>field2)
     *                   , array("<>", table4=>field4, table3=>field3)), 
     *              "OR"=> array(array("=", table5=>field5, table6=>field6)))
     * operators represent "AND",  "OR" its squence matters.
     * if operators is null, all operators are "AND"
     * 
     * SELECT * FROM `event` INNER JOIN `event_report` INNER JOIN `member` 
     * ON `table1`.`field1` = `table2`.`field2`AND `table3`.`field3` <> `table2`.`field2`AND `table4`.`field4` <> `table3`.`field3`
     * OR `table5`.`field5` = `table6`.`field6` LIMIT 0, 10
     * 
     */

    public function joinOn($condition) {
        $this->joinOnPart = " ON ";
        $ops = array_keys($condition);
        
        if (empty($ops[0])) {
            //NO "AND", "OR" 
            $keys = array_keys($condition[$ops[0]][0]);
            $opr = $condition[$ops[0]][0][0];
            $table1 = $keys[1];
            $table2 = $keys[2];
            $this->joinOnPart = $this->joinOnPart
                    . " `" . $table1 . "`.`" . $condition[$ops[0]][0][$table1] . "` " . $opr
                    . " `" . $table2 . "`.`" . $condition[$ops[0]][0][$table2] . "`";
        } else {   //Multiple Join Conditions
            if ((count($ops) == 1))  {
                $op = $ops[0];
                $tf_pairs = $condition[$op];
                if (count($tf_pairs) == 1)
                {
                         $tf_pair = $tf_pairs[0];
                         $mkeys = array_keys($tf_pair);
                         $mopr = $tf_pair[0];
                         $mtable1 = $mkeys[1];
                         $mtable2 = $mkeys[2];
                         $this->joinOnPart = $this->joinOnPart 
                               . " `" . $mtable1 . "`.`" . $tf_pair[$mtable1] . "` " . $mopr
                               . " `" . $mtable2 . "`.`" . $tf_pair[$mtable2] . "`";  
                         return $this->joinOnPart;
                }
                foreach ($tf_pairs as $idx => $tf_pair) {
                         if (count($tf_pairs) - 1 == $idx) {
                             $mkeys = array_keys($tf_pair);
                             $mopr = $tf_pair[0];
                             $mtable1 = $mkeys[1];
                             $mtable2 = $mkeys[2];
                             $this->joinOnPart = $this->joinOnPart 
                                   . " `" . $mtable1 . "`.`" . $tf_pair[$mtable1] . "` " . $mopr
                                   . " `" . $mtable2 . "`.`" . $tf_pair[$mtable2] . "`";                           
                         } else {
                             $mkeys = array_keys($tf_pair);
                             $mopr = $tf_pair[0];
                             $mtable1 = $mkeys[1];
                             $mtable2 = $mkeys[2];
                             $this->joinOnPart = $this->joinOnPart 
                                    . " `" . $mtable1 . "`.`" . $tf_pair[$mtable1] . "` " . $mopr
                                    . " `" . $mtable2 . "`.`" . $tf_pair[$mtable2] . "`" . $op;   
                         }
                }
                return $this->joinOnPart;
            }
            foreach ($ops as $index => $op) {
                $tf_pairs = $condition[$op]; 
                if (count($tf_pairs) == 1 && $index > 0) { 
                          $tf_pair = $tf_pairs[0]; 
                          $mkeys = array_keys($tf_pair);
                          $mopr = $tf_pair[0];
                          $mtable1 = $mkeys[1];
                          $mtable2 = $mkeys[2];
                          $this->joinOnPart = $this->joinOnPart . $op
                               . " `" . $mtable1 . "`.`" . $tf_pair[$mtable1] . "` " . $mopr
                               . " `" . $mtable2 . "`.`" . $tf_pair[$mtable2] . "`";  
                } elseif (count($tf_pairs) > 1) {
                     foreach ($tf_pairs as $idx => $tf_pair) {
                          if (count($tf_pairs) - 1 == $idx) {
                              $mkeys = array_keys($tf_pair);
                              $mopr = $tf_pair[0];
                              $mtable1 = $mkeys[1];
                              $mtable2 = $mkeys[2];
                              $this->joinOnPart = $this->joinOnPart 
                                   . " `" . $mtable1 . "`.`" . $tf_pair[$mtable1] . "` " . $mopr
                                   . " `" . $mtable2 . "`.`" . $tf_pair[$mtable2] . "`";                           
                          } else {
                              $mkeys = array_keys($tf_pair);
                              $mopr = $tf_pair[0];
                              $mtable1 = $mkeys[1];
                              $mtable2 = $mkeys[2];
                              $this->joinOnPart = $this->joinOnPart 
                                    . " `" . $mtable1 . "`.`" . $tf_pair[$mtable1] . "` " . $mopr
                                    . " `" . $mtable2 . "`.`" . $tf_pair[$mtable2] . "`" . $op;   
                          }
                    }
                }              
            }
            
        }
    }

    public function select($columns, $table, $distinct = 0) {
        $this->queryType = "SELECT";
        if ($distinct == 0) {
            $this->selectPart = 'SELECT ';
        } else {
            $this->selectPart = 'SELECT DISTINCT ';         
        }
        $columns = $this->mysql_escape($columns);
        $table = $this->mysql_escape($table);

        foreach ($columns as $index => $col) {
            if ($index == count($columns) - 1) {
                $this->selectPart = $this->selectPart . $col . " FROM `" . $table . "`";
            } else {
                $this->selectPart = $this->selectPart . $col . ", ";
            }
        }
    }

    /*
     * $table @string : the name of the table
     * $columns @array : the columns array(column_name => column_value, column_name1 => column_value1)
     */

    public function update($columns, $table) {
        $columns = $this->mysql_escape($columns);
        $table = $this->mysql_escape($table);
        $this->queryType = "UPDATE";
        $this->updatePart = "UPDATE `" . $table . "` SET ";
        $size = count($columns) - 1;
        $n = 0;
        foreach ($columns as $column_index => $column_value) {
            if ($n == $size) {
                $this->updatePart = $this->updatePart . "`" . $column_index . "`='" . $column_value . "'";
            } else {
                $this->updatePart = $this->updatePart . "`" . $column_index . "`='" . $column_value . "', ";
            }
            $n++;
        }
        
    }

    /*
     * $table @string : the name of the table
     * $columns @array : the columns array(column_name => column_value, column_name1 => column_value1)
     * 
     * $keywords like TIMESTAMP, it needs to be taken care of 
     */

    public function insert($columns, $table) {
        $columns = $this->mysql_escape($columns);
        $table = $this->mysql_escape($table);
        $this->queryType = "INSERT";
        $this->insertPart = "INSERT INTO " . $table . " ( ";
        $size = count($columns) - 1;
        $n = 0;
        foreach ($columns as $column_index => $column_value) {
            if ($n == $size) {
                $this->insertPart = $this->insertPart . "`" . $column_index . "`) VALUES (";
            } else {
                $this->insertPart = $this->insertPart . "`" . $column_index . "`, ";
            }
            $n++;
        }

        $n = 0;
        foreach ($columns as $column_index => $column_value) {
            if ($n == $size) {
                if (array_key_exists($column_value, $this->keywords)) {
                    $this->insertPart = $this->insertPart . $column_value . ")";
                } else {
                    $this->insertPart = $this->insertPart . "'" . $column_value . "')";
                }
            } else {
                if (array_key_exists($column_value, $this->keywords)) {
                    $this->insertPart = $this->insertPart . $column_value . ", ";
                } else {
                    $this->insertPart = $this->insertPart . "'" . $column_value . "', ";
                }
            }
            $n++;
        }
    }

    /*
     * $table @string : the name of the table
     */

    public function delete($table) {
        $table = $this->mysql_escape($table);
        $this->queryType = "DELETE";
        $this->deletePart = "DELETE FROM " . $table;
    }

    /*
     *  $offset @int
     *  $interval @int
     */

    public function limit($offset, $interval) {
        
        $offset = (!is_null($offset)) ? $this->mysql_escape($offset) : $offset;
        $interval = $this->mysql_escape($interval);
        if (!is_null($offset)) {
            $this->limitPart = $this->limitPart . " LIMIT " . trim($offset) . ", " . trim($interval);   
        } else {
            $this->limitPart = $this->limitPart . " LIMIT " . trim($interval);            
        }
    }

    /*
     *  $column @string: column name in the database
     *  $if_desc @int: null or 1
     */

    public function orderBy($column, $if_desc = NULL) {
        $column = $this->mysql_escape($column);
        if ($if_desc == NULL) {
            $this->orderPart = $this->orderPart . " ORDER BY " . $column;
        } else {
            $this->orderPart = $this->orderPart . " ORDER BY " . $column . " DESC";
        }
    }
    
    /*
     *  $column @string: column name in the database
     */
    public function groupBy($column) {
        $column = $this->mysql_escape($column);
        $this->groupPart = $this->groupPart . " GROUP BY " . $column;
    }
    
    
    public function execute() {

        $queryStmt = $this->getStatement();
        
        $con = mysql_connect($this->dbConfigArray['host'],$this->dbConfigArray['id'],$this->dbConfigArray['pwd']);
        mysql_set_charset($this->dbConfigArray['encoding'] ,$con);
                
        if (!$con) {
            die('Could not connect: ' . mysql_error());
        }
        mysql_select_db($this->dbConfigArray['database'], $con);
        $mysql_results = mysql_query($queryStmt);
        
        if (!$mysql_results) {
            die('Could not query:' . mysql_error());
        }
        mysql_close($con);
        $this->cleanAll();
        
        return $mysql_results;
    }

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
                $this->queryStmt = $this->selectPart . $this->joinPart . $this->joinOnPart
                        . $this->wherePart . $this->groupPart . $this->orderPart . $this->limitPart; 
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
    
    public function getSelectStatement(){
        if ($this->queryType != "SELECT") {
            return null;
        }
        $this->selectStatement = null;
        $this->selectStatement = $this->selectPart . $this->joinPart . $this->joinOnPart
                           . $this->wherePart . $this->groupPart . $this->orderPart . $this->limitPart;         
        return $this->selectStatement;
    }
    
    public function cleanAll(){
        $this->queryType = "";
        $this->selectPart = "";
        $this->joinPart = "";
        $this->joinOnPart = "";
        $this->wherePart = "";
        $this->orderPart = "";
        $this->limitPart = "";
        $this->updatePart = "";
        $this->insertPart = "";
        $this->deletePart = "";
        $this->groupPart = "";
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

    function mysql_escape($content) {
        /**
         * Need to add connection in order to avoid ODBC errors here 
         */
        $con = mysql_connect($this->dbConfigArray['host'],$this->dbConfigArray['id'],$this->dbConfigArray['pwd']);
        mysql_set_charset($this->dbConfigArray['encoding'] ,$con);
        //check if $content is an array
        if (is_array($content)) {
            foreach ($content as $key => $value) {
                $content[$key] = mysql_real_escape_string($value);
            }
        } else {
            //check if $content is not an array
            mysql_real_escape_string($content);
        }
        mysql_close($con);
        return $content;
    }

 

}

?>
