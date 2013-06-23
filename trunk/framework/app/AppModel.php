<?php

/**
 * Description of AppModel
 *
 * @author Hung-Fu Aaron Chang
 */
class AppModel extends AbstractModel {

    /**
     * To deal with the database config(s)
     * @return type 
     */
    public function initialDB() {
        $this->multiDb = DbConfig::getConfig();
        $this->db = $this->multiDb[0];
        return $this->db;
    }

}

?>
