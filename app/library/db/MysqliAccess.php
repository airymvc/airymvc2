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

class MysqliAccess extends MysqlDbAccess {

    function __construct($databaseId = 0) {
		parent::__construct($databaseId);
    }

    public function execute() {

        $con = new mysqli($this->dbConfigArray['host'], 
        				  $this->dbConfigArray['id'], 
        				  $this->dbConfigArray['pwd'], 
        				  $this->dbConfigArray['database']);
        				  
		$result = $con->query($this->getStatement());
        $con->close();
        
        $this->cleanAll();
        
        return $result;
    }

}

?>
