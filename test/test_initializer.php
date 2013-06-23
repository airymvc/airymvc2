<?php
require_once '../core/PathService.php';

$root = PathService::getInstance()->getRootDir();
$coreLib = $root . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "coreLib";
 $r = Initializer::getDirectory($coreLib, TRUE);

  var_dump($r);
  echo "========xxxx======\n";
  
 
 $moduleLib = $root . DIRECTORY_SEPARATOR . "moduleLib";
 $r1 = Initializer::getDirectory($moduleLib, TRUE);

  var_dump($r1);

  
    echo "========xxxx=dddddddddd=====\n";
  $str = "/usr/local/zend/apache2/htdocs/test/history/app/coreLib;/usr/local/zend/apache2/htdocs/test/history/app/coreLib/acl;/usr/local/zend/apache2/htdocs/test/history/app/coreLib/controller;/usr/local/zend/apache2/htdocs/test/history/app/coreLib/db;/usr/local/zend/apache2/htdocs/test/history/app/coreLib/ui;/usr/local/zend/apache2/htdocs/test/history/app/coreLib/ui/form;/usr/local/zend/apache2/htdocs/test/history/app/coreLib/ui/form/components";
  
 $x =explode (";", $str);
 
 var_dump($x);
 
// echo "===== \n\n\n";
// $array_items[] = preg_replace("/\/\//si", DIRECTORY_SEPARATOR, "/usr/local/zend/apache2/htdocs/test/history/app/coreLib");
// 
// var_dump($array_items);

// Initializer::initialize();
// 
?>
