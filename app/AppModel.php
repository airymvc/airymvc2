<?php

/**
 * Description of AppModel
 *
 * @author Hung-Fu Aaron Chang
 */
class AppModel {
    
	public $db;
        
        public $multiDb = array();
	
	public function initialDB()
	{
                $Config = Config::getInstance();
		$dbConfigArray = $Config->getDBConfig();
                
                foreach ($dbConfigArray as $i => $configArray) {
		
                    //Check if the dbtype is "MySQL"
                    if (strtolower($configArray['dbtype']) == "mysql")
                    {
                        //$dbaPath = 'MysqlAccess.php';
                        $this->multiDb[$i] = new MysqlAccess();
                    }
                
                }
                $this->db = $this->multiDb[0];
                return $this->db; 
	}   
}

?>
