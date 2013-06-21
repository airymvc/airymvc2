<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'AbstractFormElement.php';
/**
 * Description of CheckBoxElement
 *
 * @author Hung-Fu Aaron Chang
 */
class CheckBoxElement extends AbstractFormElement{
    //put your code here
    public function __construct($id)
    {
        $this->setId($id);
        $this->setAttribute("type", InputType::CHECKBOX);
    }
    protected $_text;
   
    public function setText($text)
    {
        $this->_text    = $text;
    }
    
    protected function renderElements()
    {
        $inputText = "<input";
        foreach ($this->_attributes as $key => $value)
        {
            $inputText = $inputText . " " . $key ."=\"".$value ."\"";
        }
        $inputText = $inputText . ">";
        $inputText = $inputText . $this->_text;
        $this->_elementText = $inputText;      
    }
    
}

?>
