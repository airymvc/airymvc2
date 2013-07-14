<?php

/**
 * AiryMVC Framework - AppView
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

class AppView extends AbstractView{

        /**
         * This is the viewfilepath that will be used
         * 
         * @var string 
         */
	    public  $_viewFilePath;
        
        
        /**
         * Variables that have been set to this view are saved in an array
         * 
         * @var array 
         */
        protected $_variables;        
        
        /**
         *
         * Determine if the plugins will be added
         * 
         * @var Boolean 
         */
        protected $_hasScript  = false;
        
        protected $_isInLayout = false;
        
	
        protected $_path;
        
        protected $_languageService;
        
		public function __construct()
		{
          	$this->_viewFilePath = null;
          	$this->_path   = PathService::getInstance();
          	$this->_languageService = LangService::getInstance();
			
          	$existed = in_array("airy.view", stream_get_wrappers());
		  	if ($existed) {
    		  	stream_wrapper_unregister("airy.view");
		  	}
          	stream_wrapper_register('airy.view', 'StreamHelper');
		}	
        
        /**
         *
         * @throws Exception 
         */
		public function render() {
           try {
                if (!is_null($this->_viewFilePath) && file_exists($this->_viewFilePath)) {
   
                    if (!is_null($this->_variables)) {
                        foreach ($this->_variables as $name=>$value)
                        {
                            if ($value instanceof UIComponent || $value instanceof JUIComponent) {
                                $htmlValue        = $value->render();
                                $newHtmlValue     = $this->_languageService->replaceWordByKey($htmlValue);
                                ${$name}          = $newHtmlValue; 
                                $this->hasAnyScript($newHtmlValue);
                            } else {
                                ${$name} = $value;
                            }         
                        }
                    }
                    /**
                     * Deprecated
                     * @TODO: change to all upper case variables 
                     */                        
                    $httpServerHost   = $this->_path->getAbsoluteHostURL();
                    $serverHost       = $this->_path->getAbsoluteHostPath();
                    
                    $ABSOLUTE_URL     = $this->_path->getAbsoluteHostURL();
                    $SERVER_HOST      = $this->_path->getAbsoluteHostPath();
                    $LEAD_FILENAME    = Config::getInstance()->getLeadFileName();
                                                                 
                    $viewContent = file_get_contents($this->_viewFilePath);
                    $this->hasAnyScript($viewContent);
                    
                    //hasScript check if there is a need for javascript library to be added in
                    //If there is no javascript UI component, but setScript == true, we still
                    //add plugins. Otherwise, we simply do not add any libraries.
                    if ($this->_hasScript) {
                        //add plugins
                        $viewContent = $this->addPluginLib($viewContent);                       
                    }
                    
                    $viewContent = $this->_languageService->replaceWordByKey($viewContent);
                    
                    $fp = fopen("airy.view://view_content", "r+");                    
                    fwrite($fp, $viewContent);
                    fclose($fp);

                    if (!$this->_isInLayout) {
                        include "airy.view://view_content";   
                    } else {
                        return file_get_contents("airy.view://view_content");
                    }
                    
                } else {
                    throw new Exception('No View File Existed!');
                }
            } catch (Exception $e) {
                echo 'Exception: ',  $e->getMessage(), "\n";
            }
		}        
	

		/**
	 	 * @return the $viewfilepath
	 	 */
		public function getViewfilepath() {
			return $this->_viewFilePath;
		}


        public function setVariable($variableName, $value) {
              $this->_variables[$variableName] = $value;
        }
        
        
        public function setVar($variableName, $value) {
              $this->_variables[$variableName] = $value;
        }
        
		/**
	 	 * @param field_type $viewfilepath
	 	 */
		public function setViewFilePath($viewFilePath) {
			$this->_viewFilePath = $viewFilePath;
		}

        
        /**
         * Check if there is any javascript in the view
         * 
         * @param string $html
         * 
         */
        protected function hasAnyScript($html) {

            if (!$this->_hasScript) {
                preg_match_all('/<(script)(.[^><]*)?>/imU', $html, $matches); 
                $this->_hasScript = empty($matches[0]) ? false : true;
            }
            
        }
        
        public function setScriptPlugin() {
            $this->_hasScript = true;
        }
        
        protected function getPluginLib() {
            
            $pluginStr = "";
            //Get the array of css and javascript addresses from config.ini
            $libs    = Config::getInstance()->getScriptPlugin();
            $cssLibs = $libs['css'];
            $JsLibs  = $libs['script'];
            
            foreach ($cssLibs as $cssLib) {
                $pluginStr .= sprintf("<link rel='stylesheet' type='text/css' href='%s'>", $cssLib);
            }
            
            foreach ($JsLibs as $JsLib) {
                $pluginStr .= sprintf("<script src='%s'></script>", $JsLib);
            } 
            
            return $pluginStr;
        }
        
        /**
         * Add plugins into html content
         * 
         * @param type $buffer 
         */
        protected function addPluginLib($content){
            
            $pluginStr = $this->getPluginLib();
            
            preg_match_all('/<\s*(title)(.[\s*^><]*)?>(.[^<]*)?<\s*\/(title)\s*>/imU', $content, $matches, PREG_PATTERN_ORDER); 
            $title = isset($matches[0][0]) ? $matches[0][0] : null;
   
            if (!is_null($title)) {
                //Completed html; attach after <title></title>
                $replaceText = $title . $pluginStr;
                $content = str_replace($title, $replaceText, $content);
            } else {
                //Not completed html; directly attach to the begining
                $content = $pluginStr . $content;
            }
            
                        
            return $content;
        }
        
        public function isInLayout($boolFlag) {
            $this->_isInLayout = $boolFlag;
        }
        
        
        public function getViewVariables() {
            return $this->_variables;
        }
        
	
}