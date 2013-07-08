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
 *  @author: Hung-Fu Aaron Chang
 */

require_once('MysqlAccess.php');


class AcDb {
    /**
     * $db is used when there is only single db setting
     */
    public $db;

    public function __construct() {
        $this->initialDB();
    }

    public function initialDB() {
        $this->multiDb = DbConfig::getConfig();
        $acl           = new AclUtility();
        $aclDbId       = $acl->getMapDatabaseId();
        $this->db      = $this->multiDb[$aclDbId];
        
        return $this->db; 
    }

    public function getUserByUidPwd($table_name, $uid_field, $uid, $pwd_or_salt_field, 
                                    $isdelete_field = null, $isdelete= null) {

		$columns = array('*');
        if (is_null($isdelete_field) || is_null($isdelete)) {
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