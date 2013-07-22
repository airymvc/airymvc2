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


class DivContainer extends AbstractContainer{

    protected $_divText;
    
    public function render()
    {
        $divText = "<div ";
        foreach ($this->_attributes as $key => $value)
        {
            $divText .=  " " . $key ."=\"".$value ."\"";
        }
        $divText .= ">";
        
        /**
         * Render the form elements here 
         */
        foreach ($this->_elements as $key => $element)
        {
            $divText .= $element->render();
        }
        
        $divText .= "</div>";
        $this->_divText = $divText;
        return $this->_divText;
    }
}

?>
