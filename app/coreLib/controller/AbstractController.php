<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AbstractController
 *
 * @author Hung-Fu Aaron Chang
 */
abstract class AbstractController{
    //put your code here
    
        protected $model;
	protected $view;
	protected $params;
        protected $acl;

	public function initial($params){}
        public function activateAcl(){}    
	function setDefaultView(){}
        function setDefaultModel(){}	
	function setParams($params){}	
	function getParams(){}
	public function getModel(){}
	public function getView(){}
	public function setModel($model){}
	public function setView($view){}
        public function switchView($moduleName, $viewName){}
    
}

?>
