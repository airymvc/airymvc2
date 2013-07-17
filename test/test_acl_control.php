<?php

require_once '../config/lib/AclUtility.php';
require_once '../core/PathService.php';
require_once '../app/coreLib/acl/AclXmlConstant.php';

$test_acl = new AclUtility();

var_dump($test_acl->getMapTables());

echo "======test1==== \n";

$x =$test_acl->getMappingFieldByTbl(1);
var_dump($x);


echo "======test3==== \n";

$x1 =$test_acl->getAllMapTblAttr();
var_dump($x1);

echo "======test4==== \n";

//$X2 = $test_acl->getAuth(1);
//var_dump($X2);

$X3 = $test_acl->getSuccessfulDispatch();
var_dump($X3);

echo "======test5==== \n";

var_dump($test_acl->getMapDatabaseId());

echo "======test6======\n";


print_r($test_acl->getEncrytion());


echo "======done==== \n";
?>
