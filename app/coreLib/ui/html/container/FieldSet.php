<?php

/**
 * AiryMVC Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license.
 *
 * It is also available at this URL: http://opensource.org/licenses/BSD-3-Clause
 * The project website URL: https://code.google.com/p/airymvc/
 *
 * @author: Hung-Fu Aaron Chang
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
