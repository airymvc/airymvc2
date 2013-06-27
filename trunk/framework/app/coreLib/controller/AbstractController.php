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
 *
 */

/**
 * Description of AbstractController
 *
 * @author Hung-Fu Aaron Chang
 */
abstract class AbstractController{
    
        protected $model;
	protected $view;
	protected $params;
        protected $acl;
        
        const VIEW_POSTFIX = 'View';
        const ACTION_POSTFIX = 'Action';

        public function initial($params) {}

        function setDefaultView()
        {
            if (file_exists(MvcReg::getActionViewFile())) {
                $this->view->setViewFilePath(MvcReg::getActionViewFile()); 
            } else {
                $this->view->setViewFilePath(MvcReg::getViewFile());
            }
        }
        public function activateAcl(){}    
        
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
            * @param field_type $view
            */
        public function setView($view) {
            $this->view = $view;
        }
        
        
        public function switchView($moduleName, $viewName){
            $viewClassName = $viewName . self::VIEW_POSTFIX;
            $viewFile = "project". DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$moduleName .DIRECTORY_SEPARATOR
                        . "views".DIRECTORY_SEPARATOR . $viewClassName .".php";
            $this->view->setViewFilePath($viewFile);
        }
        
        public function switchToCallAction($actionName){
            $controllerName = MvcReg::getControllerName();
            $moduleName = MvcReg::getModuleName();

            $actionViewClassName = ucwords($actionName) . self::VIEW_POSTFIX;
            $actionViewFile = "project". DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$moduleName .DIRECTORY_SEPARATOR. "views".DIRECTORY_SEPARATOR .$controllerName. DIRECTORY_SEPARATOR. $actionViewClassName .".php";
            $absActionViewFile = PathService::getInstance()->getRootDir() . DIRECTORY_SEPARATOR . $actionViewFile;
        
            if (!file_exists($absActionViewFile)) {
                $name = $controllerName . "_" . $actionName;
                $actionViewClassName = $name . self::VIEW_POSTFIX;
                $actionViewFile = "project". DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$moduleName .DIRECTORY_SEPARATOR. "views".DIRECTORY_SEPARATOR . $actionViewClassName .".php";
            }
            
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
            $url = PathService::getInstance()->getFormActionURL($moduleName, $controllerName, $actionName);
            return $url;
        }
    
}

?>
