<?php
class Dispatcher{  
	
        const ALL_CONTROLLERS = "ALL_CONTROLLERS";
        const ALL_ACTIONS = "ALL_ACTIONS";
        const CONTROLLER_POSTFIX = 'Controller';
        const ACTION_POSTFIX = 'Action';
        const MODEL_POSTFIX = 'Model';
        const VIEW_POSTFIX = 'View';
	public static function dispatch($Router)  
	{     		

		$controller = $Router->getController();  
		$moduleName = $Router->getModuleName();  
		$action = $Router->getAction();
		$params = $Router->getParams(); 
		$controllerName = $Router->getControllerName();   //the name without 'Controller'
                $actionName = $Router->getActionName();
		//$controller = $moduleName . '_' . $controller;
                //The default module name must be set in config.ini
		$controllerfile = 'project'. DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR . $moduleName .DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR . $controller .'.php';
                session_start();
                Dispatcher::forward($moduleName, $controllerName, $actionName, $params, $controller, $controllerfile, $action);  
	}

	public static function forward($moduleName, $controllerName, $actionName, $params, $controller = null, $controllerfile = null, $action = null)  
	{  
                $Router = new Router();
                $Router->setDefaultModelView($controllerName); 

                $controller = (is_null($controller)) ? $controllerName.self::CONTROLLER_POSTFIX : $controller;
                $action = (is_null($action)) ? $actionName.self::ACTION_POSTFIX : $action;
                
		$controllerfile1 = 'project'. DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR . $moduleName .DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR . $controller .'.php';
                $controllerfile = (is_null($controllerfile)) ? $controllerfile1 : $controllerfile;
                
                if (file_exists($controllerfile))
		{
                    try {	
                            require_once($controllerfile);
				
				//Check special Authentication controller
				/*
				 *  If status       
				 */
                                $Config = Config::getInstance();
                                $auth_array = $Config->getAuthenticationConfig();
				if ($auth_array['use_authentication'] == "enable")
				{
                                    /**
                                     * if the controller and actions are those login related ones, 
                                     * we exclude them, let them dispatch. 
                                     */
                                    if (Authentication::isLogin($moduleName)){
                                        // need to acl rule after login
                                        // put them here
                                        //
                                        Dispatcher::toMVC($controller, $action, $params);  
                                        return;
                                    }else {
                                        $login_actions =Authentication::getLoginExcludeActions($moduleName);                                    
                                        $allows = Authentication::getAllAllows($moduleName); 
                                        if (isset($login_actions[$controllerName][$actionName])) {
                                            Dispatcher::toMVC($controller, $action, $params); 
                                            return;
                                        } else {
                                            if ($allows == self::ALL_CONTROLLERS) {
                                                Dispatcher::toMVC($controller, $action, $params); 
                                                return;
                                            } elseif (isset ($allows[$controllerName])&& ($allows[$controllerName] == self::ALL_ACTIONS)){ 
                                                Dispatcher::toMVC($controller, $action, $params); 
                                                return;
                                            } 
                                            if (isset($allows[$controllerName])) {
                                                $allowActions = $allows[$controllerName];
                                                foreach ($allowActions as $idx=>$allowAction) {
                                                         if ($allowAction == $actionName) {
                                                             Dispatcher::toMVC($controller, $action, $params);
                                                             return;
                                                         }
                                                } 
                                            } 
                                            $loginControllerName = Authentication::getLoginController($moduleName);
                                            $loginController = Authentication::getLoginController($moduleName).self::CONTROLLER_POSTFIX;
                                            $loginAction = Authentication::getLoginAction($moduleName).self::ACTION_POSTFIX;
                                            $router = new Router();
                                            $router->removeDefaultActionView();
                                            $router->setDefaultModelView($loginControllerName);
                                            $router->setModuleControllerAction($moduleName, $loginControllerName, $loginAction);
                                            Dispatcher::toMVC($loginController, $loginAction, $params);                 
                                        }
                                    }
				}else {            
                                    Dispatcher::toMVC($controller, $action, $params) ;
				}
			}catch(Exception $e) {
                                error_log($e->getMessage() . "no such module or controller or action");
			}   
			   
		}   
	}
        private static function toMVC($controller, $action, $params)  
	{
                global $app;
                $app = new $controller();                    
                $app->initial($params); 
		$app->$action();       
        }

}
?>