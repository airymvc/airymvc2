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

class MysqlComponent extends SqlComponent{

    function __construct($databaseId = 0) {
		parent::__construct($databaseId);
    }
    
    public function execute() {

        $con = mysql_connect($this->dbConfigArray['host'],$this->dbConfigArray['id'],$this->dbConfigArray['pwd']);
        mysql_set_charset($this->dbConfigArray['encoding'] ,$con);
                
        if (!$con) {
            die('Could not connect: ' . mysql_error());
        }
        mysql_select_db($this->dbConfigArray['database'], $con);
        $mysql_results = mysql_query($this->getStatement());
        
        if (!$mysql_results) {
            die('Could not query:' . mysql_error());
        }
        mysql_close($con);
        $this->cleanAll();
        
        return $mysql_results;
    }



    function sqlEscape($content) {
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
            $content = mysql_real_escape_string($content);
        }
        mysql_close($con);
        return $content;
    }


}

?>
