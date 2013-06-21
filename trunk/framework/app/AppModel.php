<?php

/**
 * Description of AppModel
 *
 * @author Hung-Fu Aaron Chang
 */
class AppModel {
    
	public $db;
	
	public function initialDB()
	{
                $Config = Config::getInstance();
		$dbConfig_array = $Config->getDBConfig();
		
		//Check if the dbtype is "MySQL"
		if (strtolower($dbConfig_array['dbtype']) == "mysql")
		{
                    $dbaPath = 'MysqlAccess.php';
                    $this->db = new MysqlAccess();
		}
		return $this->db; 
	}   
}

?>
