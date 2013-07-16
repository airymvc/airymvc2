<?php

require_once '../app/coreLib/acl/Authentication.php';
require_once '../config/lib/AclUtility.php';
require_once '../core/PathService.php';
require_once '../app/coreLib/acl/AclXmlConstant.php';

    echo "=====test 0=====\n";
    $auth = new Authentication();
    $v = $auth->getAllAllows("default");
    var_dump($v);
    
    echo "=====test 1=====\n";
    $v1 = $auth->getOtherExclusiveActions("default");
    var_dump($v1);    

    echo "=====test 2=====\n";
    $v2 = $auth->getLoginExcludeActions("default");
    var_dump($v1);    
    
    echo "=====test 3=====\n";
    $v3 = $auth->getLoginErrorAction("default");
    var_dump($v3);
    
    echo "=====test 4=====\n";
    $v4 = $auth->getLoginController("default");
    var_dump($v4);
?>
