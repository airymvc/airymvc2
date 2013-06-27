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
 *
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
