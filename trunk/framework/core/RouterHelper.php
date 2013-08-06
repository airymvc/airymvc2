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

/**
 * Description of pathService
 *
 * @author Hung-Fu Aaron Chang
 */
class RouterHelper {
    //put your code here
    private static $instance;
    
    const VIEW_POSTFIX = 'View';
    const CONTROLLER_POSTFIX = 'Controller';
    
    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }    
        
        return self::$instance;
    }
    /**
     * Static method
     */
    public static function getActionViewData($moduleName, $controllerName, $actionName) {
    	$instance = self::getInstance();
    	return $instance->composeActionViewData($moduleName, $controllerName, $actionName);
    }   

    public static function hyphenToCamelCase($name, $hasFirstUppercase = FALSE) {
    	$instance = self::getInstance();
    	return $instance->fromHyphenToCamelCase($name, $hasFirstUppercase);    	
    }

    public static function camelCaseToHyphen($name) {
    	$instance = self::getInstance();
    	return $instance->fromCamelCaseToHyphen($name);    	
    }
    
    public static function toCamelCase($name) {
    	$instance = self::getInstance();
    	return $instance->convertToCamelCase($name);
    }
    
    public static function getControllerFile($moduleName, $controller) {
    	$instance = self::getInstance();
    	return $instance->getControllerFileData($moduleName, $controller);    	
    }
    
    /*
     * Object methods
     */ 

    /**
     * TODO: Need a better to compose path here
     */
    private function composeActionViewData($moduleName, $controllerName, $actionName) {          
    		//ucfirst action view class name 
    		$controllerName = $this->fromHyphenToCamelCase($controllerName, FALSE);
    		$actionName     = $this->fromHyphenToCamelCase($actionName, TRUE);
    	     
    	    $actionViewData = $this->composeActionViewFileData($moduleName, $controllerName, $actionName);
            $actionViewClassName =  $actionViewData[0];
            $actionViewFile      =  $actionViewData[1];
            $absActionViewFile   =  $actionViewData[2];
           
            if (file_exists($absActionViewFile)) {
            	return array(0 => $actionViewClassName,
            			 	 1 => $actionViewFile, 
                         	 2 => $absActionViewFile);             	
            }

            //consider that $controllerName uses hyphen            
            $controllerName = $this->fromCamelCaseToHyphen($controllerName);
        	$actionViewData = $this->composeActionViewFileData($moduleName, $controllerName, $actionName);
            $actionViewClassName =  $actionViewData[0];
            $actionViewFile      =  $actionViewData[1];
            $absActionViewFile   =  $actionViewData[2];
            
            if (file_exists($absActionViewFile)) {
            	return array(0 => $actionViewClassName,
            			 	 1 => $actionViewFile, 
                         	 2 => $absActionViewFile);             	
            }            
            
            //or lower case for first word
            $controllerName = $this->fromHyphenToCamelCase($controllerName, FALSE);
            $actionName     = $this->fromHyphenToCamelCase($actionName, FALSE);
            $actionViewData = $this->composeActionViewFileData($moduleName, $controllerName, $actionName);
            $actionViewClassName =  $actionViewData[0];
            $actionViewFile      =  $actionViewData[1];
            $absActionViewFile   =  $actionViewData[2];
            if (file_exists($absActionViewFile)) {
            	return array(0 => $actionViewClassName,
            			 	 1 => $actionViewFile, 
                         	 2 => $absActionViewFile);             	
            }

            //consider that $controllerName uses hyphen and lower case for first word           
            $controllerName = $this->fromCamelCaseToHyphen($controllerName);
            $actionName     = $this->fromHyphenToCamelCase($actionName, FALSE);
        	$actionViewData = $this->composeActionViewFileData($moduleName, $controllerName, $actionName);
            $actionViewClassName =  $actionViewData[0];
            $actionViewFile      =  $actionViewData[1];
            $absActionViewFile   =  $actionViewData[2];     
            if (file_exists($absActionViewFile)) {
            	return array(0 => $actionViewClassName,
            			 	 1 => $actionViewFile, 
                         	 2 => $absActionViewFile);             	
            } 


            //fianlly, index_actionView.php
            $name = $this->fromHyphenToCamelCase($controllerName, TRUE) . "_" . $this->fromHyphenToCamelCase($actionName, TRUE);
            $actionViewClassName = $name . self::VIEW_POSTFIX;
            $actionViewFile = "project". DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$moduleName .DIRECTORY_SEPARATOR. "views".DIRECTORY_SEPARATOR . $actionViewClassName .".php";
            $absActionViewFile = PathService::getInstance()->getRootDir() . DIRECTORY_SEPARATOR . $actionViewFile;
                        
            return array(0 => $actionViewClassName,
            			 1 => $actionViewFile, 
                         2 => $absActionViewFile);        	
    }
    
    private function composeAbsoluteActionViewFile($moduleName, $controllerName, $actionName) {
			$actionViewFiledata = $this->composeActionViewFileData($moduleName, $controllerName, $actionName);
            return $actionViewFiledata[2];   	
    }
    
    private function composeActionView($moduleName, $controllerName, $actionName) {
			$actionViewFiledata = $this->composeActionViewFileData($moduleName, $controllerName, $actionName);
            return $actionViewFiledata[1];   	
    }
    
    private function composeActionViewClassName($actionName) {
			$actionViewClassName =  $actionName. self::VIEW_POSTFIX;
            return $actionViewClassName;
    }
    
    private function composeActionViewFileData($moduleName, $controllerName, $actionName) {
    		$actionViewClassName =  $this->composeActionViewClassName($actionName);
            $actionViewFile = "project". DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$moduleName .DIRECTORY_SEPARATOR. "views".DIRECTORY_SEPARATOR .$controllerName. DIRECTORY_SEPARATOR. $actionViewClassName .".php";
            $absActionViewFile = PathService::getRootDir() . DIRECTORY_SEPARATOR . $actionViewFile; 
            return array($actionViewClassName,
            			 $actionViewFile, 
                         $absActionViewFile);   	
    }
    
    /**
     * 
     * Change hyphen name into camel case
     * @param String $name
     */
	private function fromHyphenToCamelCase($name, $hasFirstUppercase = FALSE) {
		$words = explode('-', strtolower($name));
		if (count($words) == 1) {
			$hyphenName = $this->fromCamelCaseToUcHyphen($name);
			$hyphenNameParts = explode("-", $hyphenName);
			if ($hasFirstUppercase) {
				$hyphenNameParts[0] = ucfirst($hyphenNameParts[0]);
				$oneName = join("", $hyphenNameParts);
			} else {
				if(false === function_exists('lcfirst')) {
				   $hyphenNameParts[0] = $this->lcFirst($hyphenNameParts[0]);
				} else {
				   $hyphenNameParts[0] = lcfirst($hyphenNameParts[0]);
				}
				$oneName = join("", $hyphenNameParts);				
			}
			return $oneName;
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
	
	private function convertToCamelCase($name) {
		$name = preg_replace('/(?<=\\w)(?=[A-Z])/','_$1', $name);
		$name = strtolower($name);
    	$words = explode('_', strtolower($name));
    	$camelCaseName = '';
    	
    	foreach ($words as $word) {
    		$camelCaseName .= ucfirst(trim($word));
    	}
    	    	
    	return $camelCaseName;
	}
	
	private function fromCamelCaseToHyphen($name) {
		$name = preg_replace('/(?<=\\w)(?=[A-Z])/','-$1', $name);
		$name = strtolower($name);    	    	
    	return $name;
	}
	
	private function fromCamelCaseToUcHyphen($name) {
		$name = preg_replace('/(?<=\\w)(?=[A-Z])/','_$1', $name);
		$name = strtolower($name);
		$words = explode('_', strtolower($name));    	
    	foreach ($words as $key => $word) {
    		$words[$key] = ucfirst(trim($word));
    	}
    	$name = join("-", $words);    	  	    	
    	return $name;
	}
	
	private function getControllerFileData($moduleName, $controller) {
		$controllerfile = 'project'. DIRECTORY_SEPARATOR
						 .'modules'.DIRECTORY_SEPARATOR 
						 . $moduleName .DIRECTORY_SEPARATOR
						 .'controllers'.DIRECTORY_SEPARATOR 
						 . $controller .'.php';	
        return 	$controllerfile;
	}
	
    private function lcFirst($str) {
       	$str[0] = strtolower($str[0]);
       	return (string)$str;
   	}

}

?>