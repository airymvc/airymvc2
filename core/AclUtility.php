<?php
/**
 * AiryMVC Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license.
 *
 * It is also available at this URL: http://opensource.org/licenses/BSD-3-Clause
 *
 * @author: Hung-Fu Aaron Chang
 */

require_once 'AclXmlConstant.php';

class AclUtility 
{
    private static $instance;
    private $_aclxml = null;
        
    function __construct() {
        $root = PathService::getRootDir();
        $this->_aclxml = $root .DIRECTORY_SEPARATOR . 'project' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'acl.xml';
        //Fallback to framework level's aclxml file
        $frameworkAclXml = $root . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'acl.xml';
        
        if (!file_exists($this->_aclxml)) {
            $this->_aclxml = $frameworkAclXml;
        }
    }
    
    /**
     *  Use Singleton pattern here
     */
    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }    
        
        return self::$instance;
    }
    
    public function setAclXml($aclxml) {
    	$this->_aclxml = $aclxml;
    }

//    public function loadXMLtoDOM($file) {
//
//        $this->_xmldom->load($file);
//    }

    public function getAuthentications() {
    	$loginRelatedActions = array("sign_in_action", 
    								 "login_action", 
    								 "login_error_action", 
    								 "logout_action"
    								);
        $xmldom = $this->getDOMfromXML($this->_aclxml);
        $authdom = $xmldom->getElementsByTagName(AclXmlConstant::ACL_AUTHENTICATION)->item(0);
        $auth_list = $authdom->getElementsByTagName(AclXmlConstant::MODULE);
        $auth = array();
        for ($i = 0; $i < $auth_list->length; $i++) {
            $node = $auth_list->item($i);  
            $module_name = $node->getAttribute(AclXmlConstant::NAME);
            foreach ($node->childNodes as $child) {
                 if ($child->nodeType == 1) {
                 	if ($child->nodeName != AclXmlConstant::ACL_OTHER_EXCLUSIVE_ACTIONS) {
                 		$value = trim($child->nodeValue);
                 		if (trim($child->nodeName) == "controller") {
                 			$value = RouterHelper::hyphenToCamelCase($value, TRUE);
                 		}
                 		if (in_array(trim($child->nodeName), $loginRelatedActions)) {
                  			$value = RouterHelper::hyphenToCamelCase($value);                			
                 		}
                        $auth[$module_name][trim($child->nodeName)] = $value;
                 	} else {
                 		$ex_action_nodes = $child->getElementsByTagName(AclXmlConstant::ACTION);
                 		foreach ($ex_action_nodes as $ex_action_node) {
                 			if ($ex_action_node->nodeType == 1) {
                 				$exValue = trim($ex_action_node->nodeValue);
                 				$exValue = RouterHelper::hyphenToCamelCase($exValue);  
                         		$auth[$module_name][AclXmlConstant::ACL_OTHER_EXCLUSIVE_ACTIONS][] = $exValue;               				
                 			}
                 		}
                 	}
                 }
            }   
           
        }

        return $auth;
    }

    public function getSuccessfulDispatch() {
        $xmldom = $this->getDOMfromXML($this->_aclxml);
        $dispdom = $xmldom->getElementsByTagName(AclXmlConstant::ACL_SUCCESSFUL_DISPATCH)->item(0);
        $resp_list = $dispdom->getElementsByTagName(AclXmlConstant::MODULE);
        $resp = array();
        for ($i = 0; $i < $resp_list->length; $i++) {
            $node = $resp_list->item($i);  
            $module_name = $node->getAttribute(AclXmlConstant::NAME);
            foreach ($node->childNodes as $child) {
                 if ($child->nodeType == 1) {
                 	 $value = trim($child->nodeValue);
                 	 if (trim($child->nodeName) == "controller") {
                 		 $value = RouterHelper::hyphenToCamelCase($value, TRUE);
                 	 }
                 	 if (trim($child->nodeName) == "action") {
                 	 	 $value = RouterHelper::hyphenToCamelCase($value);
                 	 }
                        $resp[$module_name][trim($child->nodeName)] = $value;
                 }
            }   
           
        }

        return $resp;
    }

    public function getDOMfromXML($xml) {
        $xmldom = new DOMDocument();
        $xmldom->load($xml);
        return $xmldom;
    }

    public function getAllMapTblAttr() {
        $tbl = $this->getMapTables();
        $map_tbl_attrs = array();
        foreach ($tbl as $table_id => $table_name) {
            $map_tbl_attrs[$table_id] = $this->getMappingFieldByTbl($table_id);
        }
        return $map_tbl_attrs;
    }
    
    public function getTableById($tbl_id){
        $tbls =  $this->getMapTables();
        return $tbls[$tbl_id];
    }  

    public function getMapDatabaseId($mapTableId) {
        $mapDbIds = $this->getMapDatabaseIds();
        return $mapDbIds[$mapTableId][AclXmlConstant::ACL_MAPPING_DB_ID];
    }
    
    public function getMapDatabaseIds() {
        $xmldom = $this->getDOMfromXML($this->_aclxml);
        $mapTbls = $xmldom->getElementsByTagName(AclXmlConstant::ACL_MAPPING_TABLE);
        $mapDbs = array();
        $tmp_types = null;
        for ($i = 0; $i < $mapTbls->length; $i++) {
            $node = $mapTbls->item($i);
            $tbId = $node->getAttribute('id');
            $dbId = $xmldom->getElementsByTagName(AclXmlConstant::ACL_MAPPING_DB_ID)->item(0)->nodeValue;
            $mapDbs[$tbId] = array(AclXmlConstant::ACL_MAPPING_DB_ID => $dbId);
        }
        
        return $mapDbs;
    }

    
    public function getEncrytion() {
    	$xmldom = $this->getDOMfromXML($this->_aclxml);
        $tblist = $xmldom->getElementsByTagName(AclXmlConstant::ACL_MAPPING_TABLE);
        $tables = array();

        for ($i = 0; $i < $tblist->length; $i++) {
            $tb = $tblist->item($i);
            $tb_id = $tb->getAttribute("id");
            $encryption = array();
            $use_encrypt = $tb->getElementsByTagName(AclXmlConstant::ACL_USE_PWD_ENCRYTION)->item(0);
            if (!is_null($use_encrypt)) {
				$encryption[AclXmlConstant::ACL_USE_PWD_ENCRYTION] = $use_encrypt->nodeValue;
            }
            $encrypt_option = $tb->getElementsByTagName(AclXmlConstant::ACL_ENCRYPTION_OPTION)->item(0);
            if (!is_null($encrypt_option)) {
				$encryption[AclXmlConstant::ACL_ENCRYPTION_OPTION] = $encrypt_option->nodeValue;
            }
            $enable_method = $tb->getElementsByTagName(AclXmlConstant::ACL_ENCRYTION_METHOD)->item(0);
            if (!is_null($enable_method)) {
				$encryption[AclXmlConstant::ACL_ENCRYTION_METHOD] = $enable_method->nodeValue;
            }
            $tables[$tb_id] = $encryption;
        }
        return $tables;
    	
    }
    

    public function getMapTables() {
        $xmldom = $this->getDOMfromXML($this->_aclxml);
        $tblist = $xmldom->getElementsByTagName(AclXmlConstant::ACL_MAPPING_TABLE);
        $tables = array();

        for ($i = 0; $i < $tblist->length; $i++) {
            $tb = $tblist->item($i);
            $tb_id = $tb->getAttribute("id");
            $tb_name = $tb->getElementsByTagName(AclXmlConstant::NAME)->item(0)->nodeValue;
            if (!is_null($tb_name)) {
                $tables[$tb_id] = $tb_name;
            }
        }
        return $tables;
    }
    
    public function getMappingModuleTables()
    {
        $xmldom = $this->getDOMfromXML($this->_aclxml);
        $mdom = $xmldom->getElementsByTagName(AclXmlConstant::ACL_MODULE_TABLE_MAPPING)->item(0);
        $map_tables = array();
        
        $node = $mdom->getElementsByTagName(AclXmlConstant::MODULE);  
        for ($i = 0; $i < $node->length; $i++) {
            $m_node = $node->item($i);
            $module_name = $m_node->getAttribute(AclXmlConstant::NAME);
            $map_tables[$i] = $module_name;
                foreach ($m_node->childNodes as $child) {
                    if (strtolower($child->nodeName) == AclXmlConstant::ACL_REFERRING_MAPPING_ID) {
                        $map_tables[$module_name] = $child->nodeValue;
                    }
                }
            
        }   
        return $map_tables;
    }
    
    public function getTableIdByModule($moduleName)
    {
        $mtbl = $this->getMappingModuleTables();
        return $mtbl[$moduleName];
    }

    public function getMappingFieldByTbl($tb_id) {
        $xmldom = $this->getDOMfromXML($this->_aclxml);
        $map_tbls = $xmldom->getElementsByTagName(AclXmlConstant::ACL_MAPPING_TABLE);
        $map_fields = array();
        $tmp_types = null;
        for ($i = 0; $i < $map_tbls->length; $i++) {
            
            $node = $map_tbls->item($i);
            //$id = $node->getAttribute('id');
            if ($node->getAttribute('id') == $tb_id) {
                $mfields = $node->getElementsByTagName(AclXmlConstant::ACL_MAPPING_FIELDS)->item(0);
                foreach ($mfields->childNodes as $child) {
                    if (($child->nodeType == 1) && (strtolower($child->nodeName) != AclXmlConstant::ACL_ROLE_SET)) {
                        // echo $child->nodeName ."=>". $child->nodeValue ."\n";
                        $map_fields[trim($child->nodeName)] = trim($child->nodeValue);
                    } else if (strtolower($child->nodeName) == AclXmlConstant::ACL_ROLE_SET) {
                        $tmp_types = explode(",", trim($child->nodeValue));
                    }
                }
            }
        }
        if (is_null($tmp_types)) {
            return $map_fields;   
        }
        
        $types = array();
        foreach ($tmp_types as $type) {
            $types[] = trim($type);
        }
        $map_fields[AclXmlConstant::ACL_ROLE_SET] = $types;
        
        return $map_fields;
    }
    


    public function getRoleTypesByTbl($tbl_name) {
        $xmldom = $this->getDOMfromXML($this->_aclxml);
        $fields = $xmldom->getElementsByTagName(AclXmlConstant::ACL_MAPPING_FIELDS);
        $map_fields = array();
        $tmp_types = null;
        for ($i = 0; $i < $fields->length; $i++) {
            $node = $fields->item($i);
            if ($node->parentNode->getAttribute('name') == $tbl_name) {
                foreach ($node->childNodes as $child) {
                    if (strtolower($child->nodeName) == AclXmlConstant::ACL_ROLE_SET) {
                        $tmp_types = explode(",", trim($child->nodeValue));
                    }
                }
            }
        }
        $types = array();
        foreach ($tmp_types as $type) {
            $types[] = trim($type);
        }
        $map_fields[AclXmlConstant::ACL_ROLE_SET] = $types;
        return $map_fields;
    }
    
    public function getLoginedAccessRules(){
        $xmldom = $this->getDOMfromXML($this->_aclxml);
        $rdom = $xmldom->getElementsByTagName(AclXmlConstant::ACL_ACCESS_RULES_AFTER_AUTHENTICATION)->item(0);
        $mdom = $rdom->getElementsByTagName(AclXmlConstant::MODULE);
        $rules = array();
        for ($i = 0; $i< $mdom->length; $i++) {
             $node = $mdom->item($i);
             $module_name = $node->getAttribute(AclXmlConstant::NAME);
             $map_id_node = $node->getElementsByTagName(AclXmlConstant::ACL_REFERRING_MAPPING_ID)->item(0);
             if (!is_null($map_id_node)) {
                 if ($map_id_node->nodeType == 1) {
                     $rules[$module_name][AclXmlConstant::ACL_REFERRING_MAPPING_ID] = $map_id_node->nodeValue;
                 }
             }
                 $rule_node = $node->getElementsByTagName(AclXmlConstant::ACL_RULE);
                 for ($j = 0; $j < $rule_node->length; $j++) {                  
                      $rule_dom = $rule_node->item($j);
                      $access_node = $rule_dom->getElementsByTagName("access")->item(0);
                      $access_type = $access_node->getAttribute("type");
                      $rules[$module_name][AclXmlConstant::ACL_RULE][$j]["access_type"] = $access_type;
                      $ref_node = $access_node->getElementsByTagName(AclXmlConstant::ACL_REFERRING_ROLE)->item(0);
                      if (!is_null($ref_node)) {
                          if ($ref_node->nodeType == 1) {
                              $rules[$module_name][AclXmlConstant::ACL_RULE][$j][AclXmlConstant::ACL_REFERRING_ROLE] = $ref_node->nodeValue;
                          }
                      }else{
                           $rules[$module_name][AclXmlConstant::ACL_RULE][$j][AclXmlConstant::ACL_REFERRING_ROLE] = null;
                      }
                      
                      foreach ($access_node->childNodes as $child) {
                          if ($child->nodeType == 1) {
                              $rules[$module_name][AclXmlConstant::ACL_RULE][$j][$child->nodeName] = $child->nodeValue;
                          } 
                      }
                 }
             
          }
  
        
        return $rules;
    }

    public function getBrowseRules(){
        $xmldom = $this->getDOMfromXML($this->_aclxml);
        $rdom = $xmldom->getElementsByTagName(AclXmlConstant::ACL_ACCESS_CONTROL_EXCLUSION)->item(0);
        $mdom = $rdom->getElementsByTagName(AclXmlConstant::MODULE);
        $rules = array();
        for ($i = 0; $i< $mdom->length; $i++) {
             $node = $mdom->item($i);
             $module_name = $node->getAttribute(AclXmlConstant::NAME);

             $anode = $node->getElementsByTagName(AclXmlConstant::ACL_ALLOW);
             if ($anode->length == 0) {
                 $rules[$module_name] = AclXmlConstant::ALL_CONTROLLERS;
                 continue;  
             }

             for ($j = 0; $j < $anode->length; $j++) {
                     
                      $adom = $anode->item($j);
                      $controller_node = $adom->getElementsByTagName(AclXmlConstant::CONTROLLER)->item(0);
                       if (!is_null($controller_node)) {
                          if ($controller_node->nodeType == 1) {
                              $controllerName = $controller_node->nodeValue;
                              $controllerName = RouterHelper::hyphenToCamelCase($controllerName, TRUE);
                          }
                      }             
                      $act_node = $adom->getElementsByTagName(AclXmlConstant::ACTION);
                      if ($act_node->length == 0) {
                          $rules[$module_name][$controllerName] = AclXmlConstant::ALL_ACTIONS;
                          continue;  
                      }
                      $action_rules = array();
                      for ($k = 0; $k < $act_node->length; $k++) {                     
                           $action = $act_node->item($k);
                           if (!is_null($action)) {
                                if ($action->nodeType == 1) {
                                	$actionName = $action->nodeValue;
                                	$actionName = RouterHelper::hyphenToCamelCase($actionName);
                                    $action_rules[$k] = $actionName;
                                }
                           }       
                      }
                      $rules[$module_name][$controllerName] = $action_rules;
               }
             
          }
  
        
        return $rules;
    }
    
}