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

interface DbAccessInterface {
	
    public function select($columns, $table, $distinct = 0);
    
    public function where($condition);
    
    public function andWhere($opString);
    
    public function orWhere($opString);
    
    public function InWhere($in);
    
    public function innerJoin($tables);
    
    public function orJoinOn($condition);
    
    public function andJoinOn($condition);

    public function update($columns, $table);
    
    public function insert($columns, $table);  
    
    public function delete($table);
    
    public function execute();
    
    public function getStatement();
    
    public function groupBy($column);
    
    public function joinOn($condition);
    
    public function limit($offset, $interval);

    public function orderBy($column, $if_desc = NULL);

	
	
}