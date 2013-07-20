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

class DbConfig {

    public static function getConfig() {
        $config = Config::getInstance();
        $dbConfigArray = $config->getDBConfig();
        $dbConfigs = array();

        foreach ($dbConfigArray as $idx => $configArray) {
            $databaseType = strtolower($configArray['dbtype']);
            if ($databaseType == "mysql") {
                $dbConfigs[$idx] = new MysqlAccess($idx);
            }
        }
        return $dbConfigs;
    }

}
?>
