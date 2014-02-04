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

require_once 'AbstractFormElement.php';

/**
 * Description of CheckBoxElement
 *
 * @author Hung-Fu Aaron Chang
 */
class CheckBoxElement extends AbstractFormElement{
    //put your code here
    public function __construct($id) {
        $this->setId($id);
        $this->setAttribute("type", InputType::CHECKBOX);
    }
    protected $_text;
   
    public function setText($text) {
        $this->_text    = $text;
    }
    
    public function getText($text) {
       return  $this->_text;
    }
    
    protected function renderElements() {
        $insert = "";
        foreach ($this->_attributes as $key => $value)
        {
            $insert .= sprintf(" %s='%s'", $key, $value);
        }
        $inputText = "<input{$insert}>". $this->_text;
        $this->_elementText = $inputText;      
    }
    
}

?>
