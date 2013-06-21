
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'FieldElement.php';
require_once 'InputType.php';
/**
 * Description of field
 *
 * @author Hung-Fu Aaron Chang
 */
class TextElement extends FieldElement{
    //put your code here
    protected $_type  = InputType::TEXT;
    
    public function __construct($id)
    {
        $this->setId($id);
        $this->setAttribute("type", $this->_type);
    }
}

?>
