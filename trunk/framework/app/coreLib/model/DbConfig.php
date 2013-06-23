<?php

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
