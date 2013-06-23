<?php

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
