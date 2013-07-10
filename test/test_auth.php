<?php

require_once '../app/coreLib/acl/Authentication.php';
require_once '../config/lib/AclUtility.php';
require_once '../core/PathService.php';
require_once '../app/coreLib/acl/AclXmlConstant.php';

    echo "=====test 0=====";
    $auth = new Authentication();
    $v = $auth->getAllAllows("default");
    var_dump($v);
    
    echo "=====test 1=====";
    $v1 = $auth->getOtherExclusiveActions("default");
    var_dump($v1);    

    echo "=====test 2=====";
    $v2 = $auth->getLoginExcludeActions("default");
    var_dump($v1);    
    
    echo "=====test 3=====";
    $v3 = $auth->getLoginErrorAction("default");
    var_dump($v3);
?>
