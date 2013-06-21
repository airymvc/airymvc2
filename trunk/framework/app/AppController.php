<?php
class AppController extends AbstractController
{
	
        protected $model;
        protected $view;
        protected $params;
        protected $acl;

        const VIEW_POSTFIX = 'View';
        const ACTION_POSTFIX = 'Action';

        public function initial($params)
        {

            $this->setDefaultModel();
            $this->view = new AppView();
            $this->setDefaultView();
            $this->setParams($params);
        }

        function setDefaultView()
        {
            if (file_exists(MvcReg::getActionViewFile())) {
                $this->view->setViewFilePath(MvcReg::getActionViewFile()); 
            } else {
                $this->view->setViewFilePath(MvcReg::getViewFile());
            }
        }
        function setDefaultModel()
        {
            if (file_exists(MvcReg::$_modelFile)) {
                require_once (MvcReg::$_modelFile); 
                $this->model = new MvcReg::$_modelClassName(); 
            }
        }

        function setParams($params)
        {
            $this->params = $params;
        }

        function getParams()
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
            * @return the $view
            */
        public function getView() {
            return $this->view;
        }

        /**
            * @param field_type $model
            */
        public function setModel($model) {
            $this->model = $model;
        }

        /**
            * @param field_type $view
            */
        public function setView($view) {
            $this->view = $view;
        }

        public function switchView($moduleName, $viewName){
            $viewClassName = $viewName . self::VIEW_POSTFIX;
            $viewFile = "modules".DIRECTORY_SEPARATOR.$moduleName .DIRECTORY_SEPARATOR
                        . "views".DIRECTORY_SEPARATOR . $viewClassName .".php";
            $this->view->setViewFilePath($viewFile);
        }

        public function switchToCallAction($actionName){
            $controllerName = MvcReg::getControllerName();
            $moduleName = MvcReg::getModuleName();

            $actionViewClassName = ucwords($actionName) . self::VIEW_POSTFIX;
            $actionViewFile = "modules".DIRECTORY_SEPARATOR.$moduleName .DIRECTORY_SEPARATOR. "views".DIRECTORY_SEPARATOR .$controllerName. DIRECTORY_SEPARATOR. $actionViewClassName .".php";
            $absActionViewFile = PathService::getInstance()->getRootDir() . DIRECTORY_SEPARATOR . $actionViewFile;
        
            if (!file_exists($absActionViewFile)) {
                $name = $controllerName . "_" . $actionName;
                $actionViewClassName = $name . self::VIEW_POSTFIX;
                $actionViewFile = "modules".DIRECTORY_SEPARATOR.$moduleName .DIRECTORY_SEPARATOR. "views".DIRECTORY_SEPARATOR . $actionViewClassName .".php";
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
