<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'AbstractFormElement.php';
/**
 * Description of RadioElement
 *
 * @author Hung-Fu Aaron Chang
 */
class RadioElement extends AbstractFormElement{
    //put your code here
    public function __construct($id)
    {
        $this->setId($id);
        $this->setAttribute("type", InputType::RADIO);
    }
    protected $_text;
   
    public function setText($text)
    {
        $this->_text    = $text;
    }
    
    public function render()
    {
        $inputText = "<input";
        foreach ($this->_attributes as $key => $value)
        {
            $inputText = $inputText . " " . $key ."=\"".$value ."\"";
        }
        $inputText = $inputText . ">";
        $inputText = $inputText . $this->_text;
        $this->_elementText = $inputText;
        
        return $this->_elementText;
    }
}

?>
