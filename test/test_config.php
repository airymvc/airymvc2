<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../core/PathService.php';
require_once '../config/lib/Config.php';

$v1 = Config::getInstance()->getScriptPlugin();
$v2 = Config::getInstance()->getDBConfig();
var_dump($v1);
var_dump($v2);
?>
