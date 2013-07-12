<?php

require_once dirname(__FILE__) . '/../app/coreLib/acl/LoginForm.php';
require_once dirname(__FILE__) . '/../app/coreLib/acl/AclXmlConstant.php';
require_once dirname(__FILE__) . '/../app/coreLib/ui/html/form/PostForm.php';
require_once dirname(__FILE__) . '/../app/coreLib/ui/html/UIComponent.php';
require_once dirname(__FILE__) . '/../core/MvcReg.php';
require_once dirname(__FILE__) . '/../app/coreLib/acl/Authentication.php';
require_once dirname(__FILE__) . '/../config/lib/AclUtility.php';
require_once dirname(__FILE__) . '/../core/PathService.php';
require_once dirname(__FILE__) . '/../config/lib/Config.php';
require_once dirname(__FILE__) . '/../app/coreLib/ui/html/components/TextElement.php';
require_once dirname(__FILE__) . '/../app/coreLib/ui/html/components/PasswordElement.php';
require_once dirname(__FILE__) . '/../app/coreLib/ui/html/components/SubmitElement.php';
require_once dirname(__FILE__) . '/../app/coreLib/ui/html/components/DivElement.php';

$loginform = new LoginForm(null, null, null, null, "default", null, null, "http://test?abc");
echo $loginform->render();

?>