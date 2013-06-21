<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'AbstractFormElement.php';
/**
 * Description of submitElement
 *
 * @author Hung-Fu Aaron Chang
 */
class SubmitElement extends AbstractFormElement{
    //put your code here
    protected $_type  = InputType::SUBMIT;
    
    public function __construct($id, $label = null)
    {
        $this->setId($id);
        $this->setAttribute("type", InputType::SUBMIT);
        if (!is_null($label)) {
            $this->setAttribute("value", $label);
        }
    }
}

?>
