
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
class TextAreaElement extends FieldElement{
    //put your code here
    protected $_type  = InputType::TEXTAREA;
    
    public function __construct($id)
    {
        $this->setId($id);
    }
    
    protected function renderElements()
    {
        $inputText = "<div id='{$this->_label_id}' class='{$this->_label_css}'>{$this->_label}</div><textarea";
        foreach ($this->_attributes as $key => $value)
        {   
            if ($key != "value"){
                $inputText = $inputText . " " . $key ."=\"".$value ."\"";
            }
        }
        $textValue = isset($this->_attributes['value'])? $this->_attributes['value'] : "";
        
        $inputText = $inputText . ">";
        $inputText = $inputText . $textValue;
        $inputText = $inputText ."</textarea>";
        $this->_elementText = $inputText;     
    }
    
}

?>
