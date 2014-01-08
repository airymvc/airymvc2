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

class MysqliComponent extends SqlComponent {

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
    
    function sqlEscape($content) {
        /**
         * Need to add connection in order to avoid ODBC errors here 
         */
        $con = mysqli_connect($this->dbConfigArray['host'],$this->dbConfigArray['id'],$this->dbConfigArray['pwd']);
        mysqli_set_charset($this->dbConfigArray['encoding'] ,$con);
        //check if $content is an array
        if (is_array($content)) {
            foreach ($content as $key => $value) {
                $content[$key] = mysqli_real_escape_string($value);
            }
        } else {
            //check if $content is not an array
            $content = mysqli_real_escape_string($content);
        }
        mysqli_close($con);
        return $content;
    }

}

?>
