<?php

class AclUtility 
{
    private static $instance;
    private $_aclxml = null;
    
    const ALL_CONTROLLERS = "ALL_CONTROLLERS";
    const ALL_ACTIONS = "ALL_ACTIONS";
                

    function __construct($aclxml = null) {
        $root = PathService::getInstance()->getRootDir();
        if (is_null($aclxml)) 
        {
            $this->_aclxml = $root . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'acl.xml';          
        } else {
            $this->_aclxml = $aclxml;
        }

    }
    
    /**
     *  Use Singleton pattern here
     */
    public static function getInstance($iniFilePath = null)
    {
        if(is_null(self::$instance)) {
            self::$instance = new self($iniFilePath);
        }    
        
        return self::$instance;
    }

    public function loadXMLtoDOM($file) {

        $this->_xmldom->load($file);
    }

    public function getAuthentications() {
        $xmldom = $this->getDOMfromXML($this->_aclxml);
        $authdom = $xmldom->getElementsByTagName("authentication")->item(0);
        $auth_list = $authdom->getElementsByTagName("module");
        $auth = array();
        for ($i = 0; $i < $auth_list->length; $i++) {
            $node = $auth_list->item($i);  
            $module_name = $node->getAttribute("name");
            foreach ($node->childNodes as $child) {
                 if ($child->nodeType == 1) {
                        $auth[$module_name][trim($child->nodeName)] = trim($child->nodeValue);
                 }
            }   
           
        }

        return $auth;
    }

    public function getSuccessfulDispatch() {
        $xmldom = $this->getDOMfromXML($this->_aclxml);
        $dispdom = $xmldom->getElementsByTagName("successful_dispatch")->item(0);
        $resp_list = $dispdom->getElementsByTagName("module");
        $resp = array();
        for ($i = 0; $i < $resp_list->length; $i++) {
            $node = $resp_list->item($i);  
            $module_name = $node->getAttribute("name");
            foreach ($node->childNodes as $child) {
                 if ($child->nodeType == 1) {
                        $resp[$module_name][trim($child->nodeName)] = trim($child->nodeValue);
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
    
    public function getMapDatabaseId() {
        $xmldom = $this->getDOMfromXML($this->_aclxml);
        $dbId = $xmldom->getElementsByTagName("mapping_database_id")->item(0)->nodeValue;
//        $tables = array();
//
//        for ($i = 0; $i < $tblist->length; $i++) {
//            $tb = $tblist->item($i);
//            $tb_id = $tb->getAttribute("id");
//            $tb_name = $tb->getElementsByTagName("name")->item(0)->nodeValue;
//            if (!is_null($tb_name)) {
//                $tables[$tb_id] = $tb_name;
//            }
//        }
        return $dbId;
    }
    
    public function getMapTables() {
        $xmldom = $this->getDOMfromXML($this->_aclxml);
        $tblist = $xmldom->getElementsByTagName("mapping_table");
        $tables = array();

        for ($i = 0; $i < $tblist->length; $i++) {
            $tb = $tblist->item($i);
            $tb_id = $tb->getAttribute("id");
            $tb_name = $tb->getElementsByTagName("name")->item(0)->nodeValue;
            if (!is_null($tb_name)) {
                $tables[$tb_id] = $tb_name;
            }
        }
        return $tables;
    }
    
    public function getMappingModuleTables()
    {
        $xmldom = $this->getDOMfromXML($this->_aclxml);
        $mdom = $xmldom->getElementsByTagName("module_table_mapping")->item(0);
        $map_tables = array();
        
        $node = $mdom->getElementsByTagName("module");  
        for ($i = 0; $i < $node->length; $i++) {
            $m_node = $node->item($i);
            $module_name = $m_node->getAttribute('name');
            $map_tables[$i] = $module_name;
                foreach ($m_node->childNodes as $child) {
                    if (strtolower($child->nodeName) == "ref_map_id") {
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
        $map_tbls = $xmldom->getElementsByTagName("mapping_table");
        $map_fields = array();
        $tmp_types = null;
        for ($i = 0; $i < $map_tbls->length; $i++) {
            
            $node = $map_tbls->item($i);
            //$id = $node->getAttribute('id');
            if ($node->getAttribute('id') == $tb_id) {
                $mfields = $node->getElementsByTagName("mapping_fields")->item(0);
                foreach ($mfields->childNodes as $child) {
                    if (($child->nodeType == 1) && (strtolower($child->nodeName) != "role_set")) {
                        // echo $child->nodeName ."=>". $child->nodeValue ."\n";
                        $map_fields[trim($child->nodeName)] = trim($child->nodeValue);
                    } else if (strtolower($child->nodeName) == "role_set") {
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
        $map_fields['role_set'] = $types;
        
        return $map_fields;
    }
    


    public function getRoleTypesByTbl($tbl_name) {
        $xmldom = $this->getDOMfromXML($this->_aclxml);
        $fields = $xmldom->getElementsByTagName("mapping_fields");
        $map_fields = array();
        $tmp_types = null;
        for ($i = 0; $i < $fields->length; $i++) {
            $node = $fields->item($i);
            if ($node->parentNode->getAttribute('name') == $tbl_name) {
                foreach ($node->childNodes as $child) {
                    if (strtolower($child->nodeName) == "role_set") {
                        $tmp_types = explode(",", trim($child->nodeValue));
                    }
                }
            }
        }
        $types = array();
        foreach ($tmp_types as $type) {
            $types[] = trim($type);
        }
        $map_fields['role_set'] = $types;
        return $map_fields;
    }
    
    public function getLoginedAccessRules(){
        $xmldom = $this->getDOMfromXML($this->_aclxml);
        $rdom = $xmldom->getElementsByTagName("after_login_access_rules")->item(0);
        $mdom = $rdom->getElementsByTagName("module");
        $rules = array();
        for ($i = 0; $i< $mdom->length; $i++) {
             $node = $mdom->item($i);
             $module_name = $node->getAttribute("name");
             $map_id_node = $node->getElementsByTagName("ref_map_id")->item(0);
             if (!is_null($map_id_node)) {
                 if ($map_id_node->nodeType == 1) {
                     $rules[$module_name]["ref_map_id"] = $map_id_node->nodeValue;
                 }
             }
                 $rule_node = $node->getElementsByTagName("rule");
                 for ($j = 0; $j < $rule_node->length; $j++) {                  
                      $rule_dom = $rule_node->item($j);
                      $access_node = $rule_dom->getElementsByTagName("access")->item(0);
                      $access_type = $access_node->getAttribute("type");
                      $rules[$module_name]["rule"][$j]["access_type"] = $access_type;
                      $ref_node = $access_node->getElementsByTagName("ref_role")->item(0);
                      if (!is_null($ref_node)) {
                          if ($ref_node->nodeType == 1) {
                              $rules[$module_name]["rule"][$j]["ref_role"] = $ref_node->nodeValue;
                          }
                      }else{
                           $rules[$module_name]["rule"][$j]["ref_role"] = null;
                      }
                      
                      foreach ($access_node->childNodes as $child) {
                          if ($child->nodeType == 1) {
                              $rules[$module_name]["rule"][$j][$child->nodeName] = $child->nodeValue;
                          } 
                      }
                 }
             
          }
  
        
        return $rules;
    }

    public function getBrowseRules(){
        $xmldom = $this->getDOMfromXML($this->_aclxml);
        $rdom = $xmldom->getElementsByTagName("before_login_browse_rules")->item(0);
        $mdom = $rdom->getElementsByTagName("module");
        $rules = array();
        for ($i = 0; $i< $mdom->length; $i++) {
             $node = $mdom->item($i);
             $module_name = $node->getAttribute("name");

             $anode = $node->getElementsByTagName("allow");
             if ($anode->length == 0) {
                 $rules[$module_name] = self::ALL_CONTROLLERS;
                 continue;  
             }

             for ($j = 0; $j < $anode->length; $j++) {
                     
                      $adom = $anode->item($j);
                      $controller_node = $adom->getElementsByTagName("controller")->item(0);
                       if (!is_null($controller_node)) {
                          if ($controller_node->nodeType == 1) {
                              $controller_name = $controller_node->nodeValue;
                          }
                      }             
                      $act_node = $adom->getElementsByTagName("action");
                      if ($act_node->length == 0) {
                          $rules[$module_name][$controller_name] = self::ALL_ACTIONS;
                          continue;  
                      }
                      $action_rules = array();
                      for ($k = 0; $k < $act_node->length; $k++) {                     
                           $action = $act_node->item($k);
                           if (!is_null($action)) {
                                if ($action->nodeType == 1) {
                                    $action_rules[$k] = $action->nodeValue;
                                }
                           }       
                      }
                      $rules[$module_name][$controller_name] = $action_rules;
               }
             
          }
  
        
        return $rules;
    }
}