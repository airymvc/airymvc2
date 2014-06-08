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
        
        protected $_inLayout = false;
        
        //protected $_path;
        
        protected $_languageService;
        
        protected $_noDoctype = false;
        
        protected $_doctype = NULL;
        
        protected $_viewScripts;
        
		public function __construct()
		{
          	$this->_viewFilePath = NULL;
          	//$this->_path   = PathService::getInstance();
          	$this->_languageService = Language::getInstance();
			
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
                            if ($value instanceof UIComponent || $value instanceof JsUIComponentInterface) {
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
                    $httpServerHost   = PathService::getAbsoluteHostURL();
                    $serverHost       = PathService::getAbsoluteHostPath();
                    
                    $ABSOLUTE_URL     = PathService::getAbsoluteHostURL();
                    $SERVER_HOST      = PathService::getAbsoluteHostPath();
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
                      
                    //$viewContent = $this->_languageService->replaceWordByKey($viewContent);
                    
                    //Check if inserting doctype at the beginning of the view content
                    if (!$this->_inLayout) {
                        if (!$this->_noDoctype) {
                    		if (is_null($this->_doctype)) {
                    			$this->setDoctype();
                    		}
                    		$viewContent = $this->_doctype . $viewContent;
                    	}  
                    } 
                    
                    $fp = fopen("airy.view://view_content", "r+");                    
                    fwrite($fp, $viewContent);
                    fclose($fp);

                    if (!$this->_inLayout) {
                    	//use ob_start to push all include files into a buffer 
                    	//and then call the callback function replaceLanguageWords
                    	//only use stream writter cannot fulfill this
                    	ob_start(array($this, 'replaceLanguageWords'));
                        include "airy.view://view_content";  
                        ob_end_flush(); 
                    } else {
                    	$this->_viewScripts = file_get_contents("airy.view://view_content");
                        return $this->_viewScripts;
                    }
                    
                } else {
                    throw new Exception("No View File {$this->_viewFilePath} Existed!");
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
            if (isset($libs['css'])) {
            	$cssLibs = $libs['css'];
            
            	foreach ($cssLibs as $cssLib) {
                	$pluginStr .= sprintf("<link rel='stylesheet' type='text/css' href='%s'>", $cssLib);
            	}
            }
            
            if (isset($libs['script'])) {
            	$JsLibs  = $libs['script'];
            	foreach ($JsLibs as $JsLib) {
                	$pluginStr .= sprintf("<script src='%s'></script>", $JsLib);
            	} 
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
        
        public function setInLayout($boolFlag) {
            $this->_inLayout = $boolFlag;
        }
        
        
        public function getViewVariables() {
            return $this->_variables;
        }
        
        
        public function setDoctype($doctype = NULL) {
        	$doctypeHandler = new Doctype();
        	$this->_doctype = $doctypeHandler->getDoctype($doctype);
        }
        
        public function noDoctype() {
			$this->_noDoctype = true;
        }
        
        public function getViewScripts() {
        	return $this->_viewScripts;
        }
        /**
         * call back function 
         * @param string $buffer
         */
        public function replaceLanguageWords($buffer) {
        	return $this->_languageService->replaceWordByKey($buffer);
        }
        
	
}