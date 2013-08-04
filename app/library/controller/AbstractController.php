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
 * Description of AbstractController
 *
 * @author Hung-Fu Aaron Chang
 */
abstract class AbstractController{
    
    	protected $params;
        
        protected $model;

        protected $layout;
        protected $view;
        
        
        //Other variables for a controller 
        protected $_viewDir;
        protected $_controllerDir;
        protected $_modelDir;

        
        const VIEW_POSTFIX = 'View';
        const ACTION_POSTFIX = 'Action';

        //The constructor function for the controller
        public function init() {}
        
        public function initial($params, $viewVariables = null) {
            $this->setDefaultModel();
            $this->view = new AppView();
            $this->setDefaultView();
            $this->setParams($params);
            $this->layout = new Layout();
            $this->layout->setView($this->view);
            $this->prepareVariables();
            
            //add view varialbes
            if (is_array($viewVariables) && !is_null($viewVariables)) {
            	foreach ($viewVariables as $variableName => $viewVariable) {
            		$this->view->setVariable($variableName, $viewVariable);
            	}
            }
        } 
        
        private function prepareVariables () {
            $modulesDir = PathService::getModulesDir();
            $moduleName = MvcReg::getModuleName();
            $this->_modelDir      = $modulesDir . DIRECTORY_SEPARATOR . $moduleName . DIRECTORY_SEPARATOR . "models"; 
            $this->_controllerDir = $modulesDir . DIRECTORY_SEPARATOR . $moduleName . DIRECTORY_SEPARATOR . "controllers";
            $this->_viewDir       = $modulesDir . DIRECTORY_SEPARATOR . $moduleName . DIRECTORY_SEPARATOR . "views";
        }

        function setDefaultView()
        {
            if (file_exists(MvcReg::getActionViewFile())) {
                $this->view->setViewFilePath(MvcReg::getActionViewFile()); 
            } else {
                $this->view->setViewFilePath(MvcReg::getViewFile());
            }
        }

        
        protected function setDefaultModel()
        {
            if (file_exists(MvcReg::$_modelFile)) {
                require_once (MvcReg::$_modelFile); 
                $this->model = new MvcReg::$_modelClassName();
                $this->model->initialDB();
            }
        }
        
        public function setParams($params)
        {
            $this->params = $params;
        }

        public function getParams()
        {
            return $this->params;
        }
        /**
            * @return the $model
            */
        public function getModel() {
            return $this->model;
        }
	
        /**
            * @param field_type $model
            */
        public function setModel($model) {
            $this->model = $model;
            $this->model->initialDB();
        }
        
        
        /**
            * @return the $view
            */
        public function getView() {
            return $this->view;
        }



        /**
            * @param AppView $view
            */
        public function setView($view) {
            $this->view = $view;
        }

        /**
         * 
         * This function can change to any view file name
         * @param string $moduleName
         * @param string $viewName
         * @param string $controllerName
         */
        public function switchView($moduleName, $viewName, $controllerName = NULL){
            $viewClassName = $viewName . self::VIEW_POSTFIX;
            if (is_null($controllerName)) {         
            	$viewFile = "project". DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$moduleName .DIRECTORY_SEPARATOR
                      	  . "views". DIRECTORY_SEPARATOR . $viewClassName .".php";
            } else {
            	$viewFile = "project". DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$moduleName .DIRECTORY_SEPARATOR
                      	  . "views". DIRECTORY_SEPARATOR . $controllerName. DIRECTORY_SEPARATOR . $viewClassName .".php";            	
            }
            $this->view->setViewFilePath($viewFile);
        }
        
        /**
         * Call another action within the same controller 
         * @param string $actionName
         */
        public function callAction($actionName){
            $controllerName = MvcReg::getControllerName();
            $moduleName = MvcReg::getModuleName();
            
            $actionViewClassName = $this->getActionViewClassName($moduleName, $controllerName, $actionName);
            $actionViewFile = $this->getActionViewFile($moduleName, $controllerName, $actionName);
            
            MvcReg::setActionViewClassName($actionViewClassName);
            MvcReg::setActionViewFile($actionViewFile); 
            
            $action = $actionName.self::ACTION_POSTFIX;
            $this->setDefaultView();
            $this->$action();
        }
        
        public function getCurrentActionURL()
        {
            $moduleName = MvcReg::getModuleName();
            $controllerName = MvcReg::getControllerName();
            $actionName = MvcReg::getActionName();
            $url = PathService::getFormActionURL($moduleName, $controllerName, $actionName);
            return $url;
        }
    
        
        protected function getViewDir() {
            return $this->_viewDir;
        }
        
        protected function getControllerDir() {
            return $this->_controllerDir;
        }
        
        protected function getModelDir(){
            return $this->_modelDir;
        }
        
        private function getActionViewClassName($moduleName, $controllerName, $actionName) {
            $viewArray = RouterHelper::getActionViewData($moduleName, $controllerName, $actionName);
            return $viewArray[0];
        }
        
        private function getActionViewFile($moduleName, $controllerName, $actionName) {
            $viewArray = RouterHelper::getActionViewData($moduleName, $controllerName, $actionName);
            return $viewArray[1];
        }
        

}

?>
