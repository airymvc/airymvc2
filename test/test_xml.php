<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/lib/AclUtility.php';
require_once '../core/PathService.php';

$test_acl = AclUtility::getInstance();

var_dump($test_acl->getMapTables());

echo "======test1==== \n";

$x =$test_acl->getMappingFieldByTbl(1);
var_dump($x);

echo "--------------------------\n";
$x =$test_acl->getMappingFieldByTbl(2);
var_dump($x);

//echo "======test2==== \n";
//
//$xy =$test_acl->getMappingFieldByTbl("admin");
//var_dump($xy);

echo "======test3==== \n";

$x1 =$test_acl->getAllMapTblAttr();
var_dump($x1);

echo "======test4==== \n";

$X2 = $test_acl->getAuthentications();
var_dump($X2);

echo "======test5==== \n";

$X3 = $test_acl->getSuccessfulDispatch();
var_dump($X3);

echo "==========test 6 ======= \n";
$xx = $test_acl->getLoginedAccessRules();
var_dump($xx);

echo "==========test 7 ======= \n";
$xx22 = $test_acl->getBrowseRules();
var_dump($xx22);

echo "==========test 8 ======= \n";
$g = $test_acl->getMappingModuleTables();
var_dump($g);

echo "======done==== \n";
?>
