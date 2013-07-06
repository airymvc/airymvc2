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

class Layout {
        
    protected $_layoutPath = null;
    
    protected $_layout = null;
    
    protected $_view;
    
    protected $_keyContents = array();
    
    
    /**
     * Variables that have been set to this layout are saved in an array
     * 
     * @var array 
     */
    protected $_variables; 
    
    const MODULE     = "module";
    const CONTROLLER = "controller";
    const ACTION     = "action";
    const PARAMS     = "params";
        
    
    public function setView($view) {
        $this->_view = $view;
    }
    
    /**
     *
     * @param string $layoutPath
     * @param array  $layout 
     * 
     * $layoutPath: the path of the layout file
     * 
     * $layout = array ($layoutKey => $viewScriptPath)
     * $layout = array ($layoutKey => array("module"     => $module [optional], 
     *                                      "controller" => $controlName, 
     *                                      "action"     => $actionName, 
     *                                      "params"     => array of params)
     * 
     */
    public function setLayout($layoutPath, $layout) {
         $this->_layoutPath = $layoutPath;
         $this->_layout     = $layout;
    }
    
    public function render(){
         //To get the layout file
         $layoutContent = file_get_contents($this->_layoutPath);
         
         $this->prepareContent($layoutContent);
         
         //Fetch each views
         $viewContents = array();
         foreach ($this->_layout as $contentKey => $viewComponent) {
             //check if it is an array that contains module controller action
             if (is_array($viewComponent)) {
                 $moduleName = MvcReg::getModuleName();
                 $moduleName = isset($viewComponent[self::MODULE]) ? $viewComponent[self::MODULE] : $moduleName;
                 try {
                     if (isset($viewComponent[self::CONTROLLER])){
                         $controllerName = $viewComponent[self::CONTROLLER];
                     } else {
                         throw new Exception('Layout is missing controller');
                     }
                     if (isset($viewComponent[self::ACTION])){
                         $actionName = $viewComponent[self::ACTION];
                     } else {
                         throw new Exception('Layout is missing controller');
                     }
                     $paramString = "";
                     if (isset($viewComponent[self::PARAMS])) {
                         $paramString =$this->getParamString($viewComponent[self::PARAMS]);
                     }
                     
                    $HttpServerHost = PathService::getInstance()->getAbsoluteHostURL();
                    $config         = Config::getInstance();
                    $LeadingUrl     = $HttpServerHost . "/" . $config->getLeadFileName();
                    $mvcKeywords    = $config->getMVCKeyword();
                    $moduleKey      = $mvcKeywords['module'];
                    $controllerKey  = $mvcKeywords['controller'];
                    $actionKey      = $mvcKeywords['action'];
                    
                    $actionPath = $moduleKey ."=". $moduleName ."&". 
                                  $controllerKey ."=". $controllerName ."&".
                                  $actionKey ."=". $actionName;
                    
                    $url = $LeadingUrl . "?" . $actionPath . $paramString;
                    $viewContent = $this->getData($url);
                    $viewContents[$contentKey] = $viewContent;
                 } catch(Exception $e) {
                     echo sprintf("View Exception: %s", $e->getMessage());
                 }
             } else if ($viewComponent instanceof AppView){
                 //Use $this->_view->render();
                 $viewComponent->isInLayout(true);
                 $viewContent = $viewComponent->render();
                 $viewContents[$contentKey] = $viewContent;
             } else {
                 $viewContent = file_get_contents($viewComponent);
                 $viewContent = LangService::getInstance()->replaceWordByKey($viewContent);
                 $viewContents[$contentKey] = $viewContent;  
                 
             }
         }
         
         /**
          * Deal with layout variables 
          */
         if (!is_null($this->_variables)) {
             foreach ($this->_variables as $name=>$value)
             {
                  if ($value instanceof UIComponent || $value instanceof JUIComponent) {
                      $htmlValue    = $value->render();
                      $newHtmlValue = LangService::getInstance()->replaceWordByKey($htmlValue);
                      ${$name}      = $newHtmlValue; 
                  } else {
                      ${$name} = $value;
                  }         
             }
         }
         
         //Loop through each contents
         //Replace view components with keywords
         $layoutContent = $this->composeContent($layoutContent, $viewContents);         
         
         //Stream output
         $existed = in_array("airy.layout", stream_get_wrappers());
		 if ($existed) {
    		 stream_wrapper_unregister("airy.layout");
		 }  
         stream_wrapper_register('airy.layout', 'StreamHelper');
         
         $fp = fopen("airy.layout://layout_content", "r+");                    
         fwrite($fp, $layoutContent);
         fclose($fp);

         include "airy.layout://layout_content"; 
    }
    
    /**
     *
     * @param array $params 
     */
    private function getParamString ($params) {
          $keyValuePair = "";
          foreach ($params as $key => $value) {
                $keyValuePair .= "&" .$key . "=" . $value;
          } 
          
          return $keyValuePair;
    }
    
    /*
     * This method uses (1) <?php echo $contentKey ?>
     *                  (2) @{contentKey}@
     */
    protected function composeContent($content, $viewContents){

        foreach ($this->_keyContents as $contentKey => $statement) {
                 $content = str_replace($statement, $viewContents[$contentKey], $content);
        }   
        preg_match_all('/(@({\w*})({\w*})@|@({\w*})@)/', $content, $matches);

        foreach ($matches[0] as $idx => $rawKey) {
                 $contentKey = str_replace('}@', '', str_replace('@{', '', $rawKey));
                 $replaceContent = $viewContents[$contentKey]; 
                 $content = str_replace($rawKey, $replaceContent, $content);
        }             
        
        return $content;
    }


    private function getData($url) {
        
        $httpClient = new HttpClient();
        
        return$httpClient->getData($url);
    }
    
    public function setVariable($variableName, $value) {
        $this->_variables[$variableName] = $value;
    }
        
        
    public function setVar($variableName, $value) {
        $this->_variables[$variableName] = $value;
    }
    
    /**
     * Array (
     *		[0] => Array (
     *       	[0] => <?php   echo    $x1  ?>
     *       	[1] => <?php   echo    $x2  ?>
     *   	)
     *		[1] => Array (
     *       	[0] => $x1
     *       	[1] => $x2
     *   	)
     *		[2] => Array (
     *       	[0] => x1
     *       	[1] => x2
     *   	)
	 *	)
     * @param string $content
     */
    public function prepareContent($content) {
    	preg_match_all('/\<\?php\s+echo\s+(\$(.*))\s?\?\>/isU', $content, $matches, PREG_PATTERN_ORDER);
    	$keyReplacements = array();
    	foreach ($matches[2] as $cn => $key) {
    		$keyReplacements[$matches[2][$cn]] = $matches[0][$cn];
    	}
    	$this->_keyContents = $keyReplacements;
    }
}
?>
