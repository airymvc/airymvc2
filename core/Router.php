<?php


/**
 * AiryMVC Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license.
 *
 * It is also available at this URL: http://opensource.org/licenses/BSD-3-Clause
 * The project website URL: https://code.google.com/p/airymvc/
 *
 * @author: Hung-Fu Aaron Chang
 */

class Router {

    // Valid constant names
    const CONTROLLER_POSTFIX = 'Controller';
    const ACTION_POSTFIX = 'Action';
    const MODEL_POSTFIX = 'Model';
    const VIEW_POSTFIX = 'View';
    const DEFAULT_PREFIX = 'index';

    private $controller;
    private $action;
    private $moduleName;
    private $params;
    private $controllerName;
    private $actionName;
    private $key_val_pairs;
    private $qstring_keys;
    private $languageCode;
    private $root;


    function __construct() {
        
        $this->root = PathService::getRootDir();        
        $config = Config::getInstance();
        $mvc_array = $config->getMVCKeyword();
        $moduleKeyword = "module";
        $controllerKeyword = "controller";
        $actionKeyword = "action";
        $languageKeyword = $config->getLanguageKeyword();
        $defaultLanguageCode = $config->getDefaultLanguage();

        
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $keys = array_keys($_GET); //get URL after '?'
        }else {
            $qstring_pieces = explode('&', $_SERVER['QUERY_STRING']);

            foreach ($qstring_pieces as $key =>$value) {
                $x = explode('=', $value);
                $this->key_val_pairs[$x[0]] = $x[1];
                $this->qstring_keys[$x[0]];
            }
            $keys = array_keys($_POST); //get form variables
        }
        
        foreach ($keys as $key => $value) {
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                //make to lower case
                $this->key_val_pairs[strtolower($value)] = $_GET[$value];
            }else {
                //make to lower case
                $this->key_val_pairs[strtolower($value)] = $_POST[$value];
            }
        }

        if (array_key_exists('module', $mvc_array)) {
            $moduleKeyword = $mvc_array['module'];
        }

        if (array_key_exists('controller', $mvc_array)) {
            $controllerKeyword = $mvc_array['controller'];
        }

        if (array_key_exists('action', $mvc_array)) {
            $actionKeyword = $mvc_array['action'];
        }

        if ($moduleKeyword == $controllerKeyword ||
            $actionKeyword == $controllerKeyword ||
            $moduleKeyword == $actionKeyword) {
            throw AiryException("Duplicate MVC Keywords");
        }

        // setup module first
        if  (!empty($this->key_val_pairs[$moduleKeyword])) {
            $this->moduleName = $this->fromHyphenToCamelCase($this->key_val_pairs[$moduleKeyword]); //module name
            $this->setModule($this->moduleName);
            unset($this->key_val_pairs[$moduleKeyword]);
        }else {
            $this->moduleName = $config->getDefaultModule(); //no module name means "default" module
            $this->setModule($this->moduleName);            
        }

        //Set Controller Name; also set the default model and view here
        if (!empty($this->key_val_pairs[$controllerKeyword])) {
            $this->controllerName = $this->fromHyphenToCamelCase($this->key_val_pairs[$controllerKeyword]);
            $this->setDefaultModelView($this->controllerName);
            MvcReg::setControllerName($this->controllerName); 
            
            $this->controller = $this->fromHyphenToCamelCase($this->key_val_pairs[$controllerKeyword]).self::CONTROLLER_POSTFIX;//controller name
            unset($this->key_val_pairs[$controllerKeyword]);

        }else {
            $this->controllerName = self::DEFAULT_PREFIX;
            $this->controller = self::DEFAULT_PREFIX.self::CONTROLLER_POSTFIX;
            $this->setDefaultModelView(self::DEFAULT_PREFIX);
            MvcReg::setControllerName($this->controllerName);
        }
        
        //Setting action 
        if  (!empty($this->key_val_pairs[$actionKeyword])) {
            $this->actionName = $this->fromHyphenToCamelCase($this->key_val_pairs[$actionKeyword]);
            MvcReg::setActionName($this->actionName);
            
            $this->action = $this->fromHyphenToCamelCase($this->key_val_pairs[$actionKeyword]).self::ACTION_POSTFIX; //action name
            unset($this->key_val_pairs[$actionKeyword]);
        }else {
            $this->actionName = self::DEFAULT_PREFIX;
            MvcReg::setActionName($this->actionName);
            $this->action = self::DEFAULT_PREFIX.self::ACTION_POSTFIX;
        }
        
        $this->setDefaultActionView($this->controllerName, $this->actionName);
        $this->setModuleControllerAction($this->moduleName, $this->controllerName, $this->actionName);
        
        //Setting language code 
        if  (!empty($this->key_val_pairs[$languageKeyword])) {
            $this->languageCode = $this->key_val_pairs[$languageKeyword];
            $this->setLanguageCode($this->languageCode);
            unset($this->key_val_pairs[$languageKeyword]);
        }else {
            $this->setLanguageCode($defaultLanguageCode);
        }
        $this->params = $this->key_val_pairs;
        Parameter::unsetAllParams();
        Parameter::setParams($this->params);    
    }

    
    public function getModuleName() {
        return $this->moduleName;
    }
    public function getAction() {
        return $this->action;
    }
    public function getController() {
        return $this->controller;
    }
    public function getParams() {
        return $this->params;
    }

    /**
     * @return the $controllerName
     */
    public function getControllerName() {
        return $this->controllerName;
    }

    /**
     * @return the $controllerName
     */
    public function getActionName() {
        return $this->actionName;
    }
    
    /**
     * @param field_type $params
     */
    public function setParams($params) {
        $this->params = $params;
    }

    /**
     * @param field_type $controllerName
     */
    public function setControllerName($controllerName) {
        $this->controllerName = $controllerName;  
    }

    public function setDefaultModelView($controllerName)
    {
        $modelClassName = $controllerName . self::MODEL_POSTFIX;
		$viewClassName = $controllerName . self::VIEW_POSTFIX;
        $modelFile = "project". DIRECTORY_SEPARATOR. "modules" .DIRECTORY_SEPARATOR. $this->moduleName .DIRECTORY_SEPARATOR."models" .DIRECTORY_SEPARATOR. $modelClassName.".php";
        $viewFile = "project". DIRECTORY_SEPARATOR. "modules".DIRECTORY_SEPARATOR.$this->moduleName .DIRECTORY_SEPARATOR. "views".DIRECTORY_SEPARATOR . $viewClassName .".php";

        MvcReg::setModelClassName($modelClassName);
        MvcReg::setViewClassName($viewClassName);
        MvcReg::setModelFile($modelFile);
        MvcReg::setViewFile($viewFile);  
    }
    
    public function setDefaultActionView($controllerName, $actionName)
    {
    	$actionViewArray = PathService::getActionViewData($this->moduleName, $controllerName, $actionName);
        $actionViewClassName = $actionViewArray[0];
        $actionViewFile = $actionViewArray[1];
        
        MvcReg::setActionViewClassName($actionViewClassName);
        MvcReg::setActionViewFile($actionViewFile);  
    }
    
    public function removeDefaultActionView(){
        MvcReg::setActionViewClassName(null);
        MvcReg::setActionViewFile(null);        
    }
    
    public function setModule($moduleName) {
        MvcReg::setModuleName($moduleName);
    }
    public function setModuleControllerAction($moduleName, $controllerName, $actionName) {
        MvcReg::setModuleName($moduleName);
        MvcReg::setControllerName($controllerName);
        MvcReg::setActionName($actionName);
    }
    
    public function setLanguageCode($languageCode) {
        LangReg::setLanguageCode($languageCode);
    }
    
    /**
     * 
     * Change hyphen name into camel case
     * @param String $name
     */
	private function fromHyphenToCamelCase($name, $hasFirstUppercase = FALSE) {
		$words = explode('-', strtolower($name));
		if (count($words) == 1) {
			return $name;
		}
		
		$camelCaseName = '';
		$index = 0;
		foreach ($words as $word) {
			if (!$hasFirstUppercase && $index ==0) {
				$camelCaseName .= trim($word);
			} else {
				$camelCaseName .= ucfirst(trim($word));
			}
			$index++;
		}
		return $camelCaseName;
	}
	
	private function fromCamelCaseToHyphen($name) {
		$name = preg_replace('/(?<=\\w)(?=[A-Z])/','_$1', $name);
		$name = strtolower($name);
    	$words = explode('-', strtolower($name));
    	$camelCaseName = '';
    	
    	foreach ($words as $word) {
    		$camelCaseName .= ucfirst(trim($word));
    	}
    	$name = $camelCaseName;
    	
    	return $name;
	}
}
?>
