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

class MysqlAccess extends MysqlDbAccess{

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

}

?>
