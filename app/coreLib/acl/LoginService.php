<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LoginSessionService
 *
 * @author Hung-Fu Aaron Chang
 */
class LoginService {
    
    private static $instance; 
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
    
    
    public static function getIdByAccount($accout_id)
    {
        $moduleName = MvcReg::getModuleName();

        $table = "member";
        
        $where = array("AND"=>array("="=>array('account_id'=>$account_id, 'isdelete' => 0)));
        $columns[0] = '*';

        $this->db->select($id, $table);
        $this->db->where($where);
        $mysql_results = $this->db->execute();	
        $memberInfo =  $mysql_results;
        
        $r = mysql_fetch_array($memberInfo, MYSQL_BOTH);
        $userid = $r['id'];
        //set back to original result
        mysql_data_seek($memberInfo, 0);

        $this->view->setVariable('memberInfo', $memberInfo);
        $this->view->setVariable('userid',$userid);
        $this->view->render();
    }
    
    /**
     *
     * @param String $moduleName
     * @return String uid 
     */
    public function getLoginUserId($moduleName = null)
    {
        if (!is_null($moduleName)) {
            return $_SESSION[$moduleName][Authentication::UID];
        }
        
        $currentModuleName = MvcReg::getModuleName();
        return $_SESSION[$currentModuleName][Authentication::UID];
        
    }
    public function getEncryptLoginUserId ($moduleName = null){
        if (!is_null($moduleName)) {
            return $_SESSION[$moduleName][Authentication::ENCRYPT_UID];
        }
        
        $currentModuleName = MvcReg::getModuleName();
        return $_SESSION[$currentModuleName][Authentication::ENCRYPT_UID];        
    }
    public function isLogin($moduleName = null)
    {
        if (!is_null($moduleName)) {
            return $_SESSION[$moduleName][Authentication::UID];
        }
        
        $currentModuleName = MvcReg::getModuleName();
        return $_SESSION[$currentModuleName][Authentication::UID];        
    }
    /**
     * setLogin : Save the session data with uid, moduleName
     * @params: String $uid, String $moduleName
     */
    public function setLogin($moduleName, $uid) {

            $_SESSION[$moduleName][Authentication::UID] = $uid;
            $_SESSION[$moduleName][Authentication::ENCRYPT_UID] = Base64UrlCode::encrypt($uid);
            $_SESSION[$moduleName][Authentication::IS_LOGIN] = true;
            $_SESSION[Authentication::UID]['module'] = $moduleName;

    }
    
    
}

?>
