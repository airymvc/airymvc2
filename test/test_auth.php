<?php

require_once '../app/coreLib/acl/Authentication.php';
require_once '../config/lib/AclUtility.php';
require_once '../core/PathService.php';
  
    $auth = new Authentication();
    $v = $auth->getAllAllows("default");
    var_dump($v);

?>
