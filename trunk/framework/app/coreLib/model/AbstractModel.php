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

abstract class AbstractModel {
    
    /**
     * $db : a database object 
     */
    public $db;

    /**
     * $multiDb : array of database objects 
     */
    public $multiDb = array();
    
    public function initialDB(){}
    
}

?>
