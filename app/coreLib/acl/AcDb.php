<?php
require_once('MysqlAccess.php');


class AcDb {
    /*
     * $db is used when there is only single db setting
     */
    public $db;

    public function __construct() {
        $this->initialDB();
    }

    public function initialDB() {
        $Config = Config::getInstance();
        $dbConfigArray = $Config->getDBConfig();

        //Check if the dbtype is "MySQL"
        if (strtolower($dbConfigArray['dbtype']) == "mysql") {
            $this->db = new MysqlAccess();
        }
        //Check if the dbtype is others....... 
    }

    public function getUserByUidPwd($table_name, $uid_field, $uid, $pwd_or_salt_field, 
                                    $isdelete_field = null, $isdelete= null) {

	$columns = array('*');
        if (is_null($isdelete_field) || is_null($isdelete))
        {
            $where = array("AND"=>array("="=>array( $uid_field  => $uid)));
        } else {
            $where = array("AND"=>array("="=>array( $uid_field  => $uid),
                                        ">"=>array( $isdelete_field > $isdelete)));            
        }
        $this->db->select($columns, $table_name);
        $this->db->where($where);
        $mysql_results = $this->db->execute();	
        return $mysql_results;
    }



}