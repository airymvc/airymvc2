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
    
    const MODULE     = "module";
    const CONTROLLER = "controller";
    const ACTION     = "action";
    const OPTION     = "option";
        
    
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
     *                                      "option"     => array of options)
     * 
     */
    
    public function setLayout($layoutPath, $layout) {
         $this->_layoutPath = $layoutPath;
         $this->_layout     = $layout;
    }
    
    public function render(){
         //To get the layout file
         $layoutContent = file_get_contents($this->_layoutPath);
         
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
                     if (isset($viewComponent[self::OPTION])) {
                         $paramString =$this->getParamString($viewComponent[self::OPTION]);
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
         
         //Loop through each contents
         //Replace view components with keywords
         $newLayoutContent = $this->composeContent($layoutContent, $viewContents);         
         
         //Stream output  
         stream_wrapper_register('airy.layout', 'StreamHelper');
         
         $fp = fopen("airy.layout://layout_content", "r+");                    
         fwrite($fp, $newLayoutContent);
         fclose($fp);

         include "airy.layout://layout_content"; 
    }
    
    /**
     *
     * @param array $options 
     */
    private function getParamString ($options) {
          $keyValuePair = "";
          foreach ($options as $key => $value) {
                $keyValuePair .= "&" .$key . "=" . $value;
          } 
          
          return $keyValuePair;
    }
    
    protected function composeContent($content, $viewContents){

        preg_match_all('/(@({\w*})({\w*})@|@({\w*})@)/', $content, $matches);
        /**
         * @TODO: Consider two level keyword like %{A}{B}% 
         */
        foreach ($matches[0] as $idx => $rawKey) {
                $tmpKey = str_replace('@{', '', $rawKey);
                $contentKey = str_replace('}@', '', $tmpKey);
                $replaceContent = $viewContents[$contentKey]; 
                $content = str_replace($rawKey, $replaceContent, $content);
        }   
            
        return $content;
    }
    

    private function getData($url) {
        
        $httpClient = new HttpClient();
        
        return$httpClient->getData($url);
    }
    
}
?>
