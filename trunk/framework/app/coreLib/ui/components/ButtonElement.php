<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'AbstractFormElement.php';
/**
 * Description of abstractButton
 *
 * @author Hung-Fu Aaron Chang
 */
class ButtonElement extends AbstractFormElement{
    //put your code here
    protected $_type  = InputType::BUTTON;
    
    public function __construct($id, $label = null)
    {
        $this->setId($id);
        $this->setAttribute("type", InputType::BUTTON);
        if (!is_null($label)) {
            $this->setAttribute("value", $label);
        }
    }
}

?>
