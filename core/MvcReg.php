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

class MvcReg{

     static $_modelClassName;
     static $_viewClassName;
     static $_actionViewClassName;
     static $_modelFile;
     static $_viewFile;
     static $_actionViewFile;
     static $_moduleName;
     static $_controllerName;
     static $_actionName;

     public static function setModelClassName($modelClassName)
     {
          self::$_modelClassName = $modelClassName;
     }
     public static function getModelClassName()
     {
          return self::$_modelClassName;
     }

     public static function setViewClassName($viewClassName)
     {
          self::$_viewClassName = $viewClassName;
     }
     public static function getViewClassName()
     {
          return self::$_viewClassName;
     }
     public static function setActionViewClassName($actionViewClassName)
     {
          self::$_actionViewClassName = $actionViewClassName;
     }
     public static function getActionViewClassName()
     {
          return self::$_actionViewClassName;
     }
     public static function setModelFile($modelFile)
     {
          self::$_modelFile = $modelFile;
     }
     public static function getModelFile()
     {
          return self::$_modelFile;
     }

     public static function setViewFile($viewFile)
     {
          self::$_viewFile = $viewFile;
     }
     public static function getViewFile()
     {
          return self::$_viewFile;
     }
     public static function setActionViewFile($actionViewFile)
     {
          self::$_actionViewFile = $actionViewFile;
     }
     public static function getActionViewFile()
     {
          return self::$_actionViewFile;
     }
     public static function setModuleName($moduleName)
     {
          self::$_moduleName = $moduleName;
     }
     public static function getModuleName()
     {
          return self::$_moduleName;
     }     
   
     public static function setControllerName($controllerName)
     {
          self::$_controllerName = $controllerName;
     }
     public static function getControllerName()
     {
          return self::$_controllerName;
     }
     
     public static function setActionName($actionName)
     {
          self::$_actionName = $actionName;
     }
     public static function getActionName()
     {
          return self::$_actionName;
     }
     
}

?>
