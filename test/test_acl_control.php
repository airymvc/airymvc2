<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../core/lib/acl/accessControl.php';
$test_acl = new acl_util();

var_dump($test_acl->getMapTables());

echo "======test1==== \n";

$x =$test_acl->getMappingFieldByTbl(1);
var_dump($x);

//echo "======test2==== \n";
//
//$xy =$test_acl->getMappingFieldByTbl("admin");
//var_dump($xy);

echo "======test3==== \n";

$x1 =$test_acl->getAllMapTblAttr();
var_dump($x1);

echo "======test4==== \n";

$X2 = $test_acl->getAuth(1);
var_dump($X2);

$X3 = $test_acl->getSuccessfulDispatch();
var_dump($X3);

echo "======done==== \n";
?>
