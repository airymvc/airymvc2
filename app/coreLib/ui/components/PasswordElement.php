<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'FieldElement.php';
require_once 'InputType.php';
/**
 * Description of passwordElement
 *
 * @author Hung-Fu Aaron Chang
 */
class PasswordElement extends FieldElement{
    //put your code here
    protected $_type = InputType::PASSWORD;
    
    public function __construct($id)
    {
        $this->setId($id);
        $this->setAttribute("type", InputType::PASSWORD);
    }
}

?>
