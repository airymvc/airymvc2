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