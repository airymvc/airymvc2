<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FieldSet
 *
 * @author Hung-Fu Aaron Chang
 */
class FieldSet extends AbstractContainer{
    //put your code here
    protected $_fsText;
    protected $_legend;
    
    public function setLabel($label){
        $this->_legend = $label;
    }
    
    public function render()
    {
        $fsText = "<fieldset";
        foreach ($this->_attributes as $key => $value)
        {
            $fsText = $fsText . " " . $key ."=\"".$value ."\"";
        }
        $fsText = $fsText . ">";
        $fsText = $fsText . "<legend>" . $this->_legend . "</legend>";      
        /**
         * Render the form elements here 
         */
        foreach ($this->_elements as $key => $element)
        {
            $fsText = $fsText . $element->render();
        }
        
        $fsText = $fsText . "</fieldset>";
        $this->_fsText = $fsText;
        
        return $this->_fsText;
    }
}

?>
