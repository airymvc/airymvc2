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
