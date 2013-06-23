<?php

/*
 *  Sep. 2, version for inner join function
 */

class MysqlAccess  {

    private $dbConfig_array;
    private $query_stmt;
    private $select_stmt;
    private $select_part;
    private $update_part;
    private $delete_part;
    private $insert_part;
    private $join_part;
    private $join_on_part;
    private $where_part;
    private $order_part;
    private $group_part;
    private $limit_part;
    private $keywords;
    private $query_type;

    function __construct($databaseId = 0) {
        $config = Config::getInstance();
        $configArray = $config->getDBConfig();
        $this->dbConfig_array = $configArray[$databaseId];
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
        $this->where_part = " WHERE ";
        $ops = array_keys($condition);
        if (empty($ops[0])) {
            //NO "AND", "OR" 
            $keys = array_keys($condition[$ops[0]]);
            $opr = $keys[0];
            $field_array = $condition[$ops[0]][$opr];
            $sub_keys = array_keys($field_array);
            $pos = strpos($sub_keys[0], '.');
            if ($pos == false){
                $this->where_part = $this->where_part
                     . " `" . $sub_keys[0] . "` " . $opr . " '" . $condition[$ops[0]][$opr][$sub_keys[0]] . "' ";
            } else {
                $tf = explode (".", $sub_keys[0]);
                $this->where_part = $this->where_part
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
                                    $this->where_part = $this->where_part
                                           . " `" . $mfield . "` " . $mopr . " '" . $fv_pair[$mfield] . "' ";
                                } else {
                                    $tf = explode (".", $mfield);
                                    $this->where_part = $this->where_part
                                           . " `{$tf[0]}`.`{$tf[1]}` " . $mopr . " '" . $fv_pair[$mfield] . "' ";                               
                                }
                                $first_one = FALSE;
                            } else {
                                $pos = strpos($mfield, '.');
                                if ($pos == false){
                                    $this->where_part = $this->where_part . strtoupper($op)
                                           . " `" . $mfield . "` " . $mopr . " '" . $fv_pair[$mfield] . "' ";
                                } else {
                                    $tf = explode (".", $mfield);
                                    $this->where_part = $this->where_part . strtoupper($op)
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
                $this->join_part = " INNER JOIN `" . $tbl . "`";
            } else {
                $this->join_part = $this->join_part . " INNER JOIN `" . $tbl . "`";
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
        $this->join_on_part = " ON ";
        $ops = array_keys($condition);
        
        if (empty($ops[0])) {
            //NO "AND", "OR" 
            $keys = array_keys($condition[$ops[0]][0]);
            $opr = $condition[$ops[0]][0][0];
            $table1 = $keys[1];
            $table2 = $keys[2];
            $this->join_on_part = $this->join_on_part
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
                         $this->join_on_part = $this->join_on_part 
                               . " `" . $mtable1 . "`.`" . $tf_pair[$mtable1] . "` " . $mopr
                               . " `" . $mtable2 . "`.`" . $tf_pair[$mtable2] . "`";  
                         return $this->join_on_part;
                }
                foreach ($tf_pairs as $idx => $tf_pair) {
                         if (count($tf_pairs) - 1 == $idx) {
                             $mkeys = array_keys($tf_pair);
                             $mopr = $tf_pair[0];
                             $mtable1 = $mkeys[1];
                             $mtable2 = $mkeys[2];
                             $this->join_on_part = $this->join_on_part 
                                   . " `" . $mtable1 . "`.`" . $tf_pair[$mtable1] . "` " . $mopr
                                   . " `" . $mtable2 . "`.`" . $tf_pair[$mtable2] . "`";                           
                         } else {
                             $mkeys = array_keys($tf_pair);
                             $mopr = $tf_pair[0];
                             $mtable1 = $mkeys[1];
                             $mtable2 = $mkeys[2];
                             $this->join_on_part = $this->join_on_part 
                                    . " `" . $mtable1 . "`.`" . $tf_pair[$mtable1] . "` " . $mopr
                                    . " `" . $mtable2 . "`.`" . $tf_pair[$mtable2] . "`" . $op;   
                         }
                }
                return $this->join_on_part;
            }
            foreach ($ops as $index => $op) {
                $tf_pairs = $condition[$op]; 
                if (count($tf_pairs) == 1 && $index > 0) { 
                          $tf_pair = $tf_pairs[0]; 
                          $mkeys = array_keys($tf_pair);
                          $mopr = $tf_pair[0];
                          $mtable1 = $mkeys[1];
                          $mtable2 = $mkeys[2];
                          $this->join_on_part = $this->join_on_part . $op
                               . " `" . $mtable1 . "`.`" . $tf_pair[$mtable1] . "` " . $mopr
                               . " `" . $mtable2 . "`.`" . $tf_pair[$mtable2] . "`";  
                } elseif (count($tf_pairs) > 1) {
                     foreach ($tf_pairs as $idx => $tf_pair) {
                          if (count($tf_pairs) - 1 == $idx) {
                              $mkeys = array_keys($tf_pair);
                              $mopr = $tf_pair[0];
                              $mtable1 = $mkeys[1];
                              $mtable2 = $mkeys[2];
                              $this->join_on_part = $this->join_on_part 
                                   . " `" . $mtable1 . "`.`" . $tf_pair[$mtable1] . "` " . $mopr
                                   . " `" . $mtable2 . "`.`" . $tf_pair[$mtable2] . "`";                           
                          } else {
                              $mkeys = array_keys($tf_pair);
                              $mopr = $tf_pair[0];
                              $mtable1 = $mkeys[1];
                              $mtable2 = $mkeys[2];
                              $this->join_on_part = $this->join_on_part 
                                    . " `" . $mtable1 . "`.`" . $tf_pair[$mtable1] . "` " . $mopr
                                    . " `" . $mtable2 . "`.`" . $tf_pair[$mtable2] . "`" . $op;   
                          }
                    }
                }              
            }
            
        }
    }

    public function select($columns, $table, $distinct = 0) {
        $this->query_type = "SELECT";
        if ($distinct == 0) {
            $this->select_part = 'SELECT ';
        } else {
            $this->select_part = 'SELECT DISTINCT ';         
        }
        $columns = $this->mysql_escape($columns);
        $table = $this->mysql_escape($table);

        foreach ($columns as $index => $col) {
            if ($index == count($columns) - 1) {
                $this->select_part = $this->select_part . $col . " FROM `" . $table . "`";
            } else {
                $this->select_part = $this->select_part . $col . ", ";
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
        $this->query_type = "UPDATE";
        $this->update_part = "UPDATE `" . $table . "` SET ";
        $size = count($columns) - 1;
        $n = 0;
        foreach ($columns as $column_index => $column_value) {
            if ($n == $size) {
                $this->update_part = $this->update_part . "`" . $column_index . "`='" . $column_value . "'";
            } else {
                $this->update_part = $this->update_part . "`" . $column_index . "`='" . $column_value . "', ";
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
        $this->query_type = "INSERT";
        $this->insert_part = "INSERT INTO " . $table . " ( ";
        $size = count($columns) - 1;
        $n = 0;
        foreach ($columns as $column_index => $column_value) {
            if ($n == $size) {
                $this->insert_part = $this->insert_part . "`" . $column_index . "`) VALUES (";
            } else {
                $this->insert_part = $this->insert_part . "`" . $column_index . "`, ";
            }
            $n++;
        }

        $n = 0;
        foreach ($columns as $column_index => $column_value) {
            if ($n == $size) {
                if (array_key_exists($column_value, $this->keywords)) {
                    $this->insert_part = $this->insert_part . $column_value . ")";
                } else {
                    $this->insert_part = $this->insert_part . "'" . $column_value . "')";
                }
            } else {
                if (array_key_exists($column_value, $this->keywords)) {
                    $this->insert_part = $this->insert_part . $column_value . ", ";
                } else {
                    $this->insert_part = $this->insert_part . "'" . $column_value . "', ";
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
        $this->query_type = "DELETE";
        $this->delete_part = "DELETE FROM " . $table;
    }

    /*
     *  $offset @int
     *  $interval @int
     */

    public function limit($offset, $interval) {
        
        $offset = (!is_null($offset)) ? $this->mysql_escape($offset) : $offset;
        $interval = $this->mysql_escape($interval);
        if (!is_null($offset)) {
            $this->limit_part = $this->limit_part . " LIMIT " . trim($offset) . ", " . trim($interval);   
        } else {
            $this->limit_part = $this->limit_part . " LIMIT " . trim($interval);            
        }
    }

    /*
     *  $column @string: column name in the database
     *  $if_desc @int: null or 1
     */

    public function orderBy($column, $if_desc = NULL) {
        $column = $this->mysql_escape($column);
        if ($if_desc == NULL) {
            $this->order_part = $this->order_part . " ORDER BY " . $column;
        } else {
            $this->order_part = $this->order_part . " ORDER BY " . $column . " DESC";
        }
    }
    
    /*
     *  $column @string: column name in the database
     */
    public function groupBy($column) {
        $column = $this->mysql_escape($column);
        $this->group_part = $this->group_part . " GROUP BY " . $column;
    }
    
    
    public function execute() {

        $query_stmt = $this->getStatement();
        
        $con = mysql_connect($this->dbConfig_array['host'],$this->dbConfig_array['id'],$this->dbConfig_array['pwd']);
        mysql_set_charset($this->dbConfig_array['encoding'] ,$con);
                
        if (!$con) {
            die('Could not connect: ' . mysql_error());
        }
        mysql_select_db($this->dbConfig_array['database'], $con);
        $mysql_results = mysql_query($query_stmt);
        
        if (!$mysql_results) {
            die('Could not query:' . mysql_error());
        }
        mysql_close($con);
        $this->cleanAll();
        
        return $mysql_results;
    }

    /**
     * @return the $dbConfig_array
     */
    public function getDbConfig_array() {
        return $this->dbConfig_array;
    }

    /**
     * @return the $query_stmt
     */
    public function getStatement() {
        //Combine every part of the query statement
        switch ($this->query_type) {
            case "SELECT":
                $this->query_stmt = null;
                $this->query_stmt = $this->select_part . $this->join_part . $this->join_on_part
                        . $this->where_part . $this->group_part . $this->order_part . $this->limit_part; 
                break;
            case "UPDATE":
                $this->query_stmt = null;
                $this->query_stmt = $this->update_part . $this->where_part;
                break;
            case "INSERT":
                $this->query_stmt = null;
                $this->query_stmt = $this->insert_part;
                break;
            case "DELETE":
                $this->query_stmt = null;
                $this->query_stmt = $this->delete_part . $this->where_part;
                break;
        }
        return $this->query_stmt;
    }
    
    public function getSelectStatement(){
        if ($this->query_type != "SELECT") {
            return null;
        }
        $this->select_stmt = null;
        $this->select_stmt = $this->select_part . $this->join_part . $this->join_on_part
                           . $this->where_part . $this->group_part . $this->order_part . $this->limit_part;         
        return $this->select_stmt;
    }
    
    public function cleanAll(){
        $this->query_type = "";
        $this->select_part = "";
        $this->join_part = "";
        $this->join_on_part = "";
        $this->where_part = "";
        $this->order_part = "";
        $this->limit_part = "";
        $this->update_part = "";
        $this->insert_part = "";
        $this->delete_part = "";
        $this->group_part = "";
    }

    /**
     * @param field_type $dbConfig_array
     */
    public function setDbConfig_array($dbConfig_array) {
        $this->dbConfig_array = $dbConfig_array;
    }

    /**
     * @param field_type $query_stmt
     */
    public function setStatement($query_stmt) {
        $this->query_stmt = $query_stmt;
    }

    public function setKeywords() {
        $this->keywords['CURRENT_TIMESTAMP'] = "CURRENT_TIMESTAMP";
    }

    function mysql_escape($content) {
        /**
         * Need to add connection in order to avoid ODBC errors here 
         */
        $con = mysql_connect($this->dbConfig_array['host'],$this->dbConfig_array['id'],$this->dbConfig_array['pwd']);
        mysql_set_charset($this->dbConfig_array['encoding'] ,$con);
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
