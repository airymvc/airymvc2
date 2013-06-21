<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DivContainer
 *
 * @author Hung-Fu Aaron Chang
 */
class DivContainer extends AbstractContainer{
    //put your code here
    protected $_divText;
    
    public function render()
    {
        $divText = "<div ";
        foreach ($this->_attributes as $key => $value)
        {
            $divText = $divText . " " . $key ."=\"".$value ."\"";
        }
        $divText = $divText . ">";
        
        /**
         * Render the form elements here 
         */
        foreach ($this->_elements as $key => $element)
        {
            $divText = $divText . $element->render();
        }
        
        $divText = $divText . "</div>";
        $this->_divText = $divText;
        return $this->_divText;
    }
}

?>
