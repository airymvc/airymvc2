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

class MysqliComponent extends MysqlCommon {

	private $port = 3306;
	private $host;
	
    function __construct($databaseId = 0) {
		parent::__construct($databaseId);
    }

    public function execute() {
		$hostArray = explode(":", $this->dbConfigArray['host']);    	
		$this->host = $hostArray[0];
		$this->port = $hostArray[1];
        $con = new mysqli($this->host, 
        				  $this->dbConfigArray['id'], 
        				  $this->dbConfigArray['pwd'], 
        				  $this->dbConfigArray['database'],
        				  $this->port);
        				  
		$result = $con->query($this->getStatement());
        $con->close();
        
        $this->cleanAll();
        
        return $result;
    }
    
    function sqlEscape($content) {
        /**
         * Need to add connection in order to avoid ODBC errors here 
         */
        $con = new mysqli($this->host, 
        				  $this->dbConfigArray['id'], 
        				  $this->dbConfigArray['pwd'], 
        				  $this->dbConfigArray['database'],
        				  $this->port);
        mysqli_set_charset($con, strtolower($this->dbConfigArray['encoding']));
        //check if $content is an array
        if (is_array($content)) {
            foreach ($content as $key => $value) {
                $content[$key] = mysqli_real_escape_string($con, $value);
            }
        } else {
            //check if $content is not an array
            $content = mysqli_real_escape_string($con, $content);
        }
        mysqli_close($con);
        return $content;
    }

}

?>
