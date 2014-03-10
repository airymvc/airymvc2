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

class RadioElement extends AbstractFormElement{
    //put your code here
    public function __construct($id)
    {
        $this->setId($id);
        $this->setAttribute("type", InputType::RADIO);
    }
    protected $_text;
    
    /**
     * Same as setText method for consistency (TextElement, TextAreaElement)
     * @param string $text
     */
    public function setLabel($text)
    {
    	$this->setText($text);
    }
    
    public function setText($text)
    {
        $this->_text    = $text;
    }
    
    public function render()
    {
        $insert = "";
        foreach ($this->_attributes as $key => $value)
        {
            $insert .= sprintf(" %s='%s'", $key, $value);
        }
        $inputText = "<input{$insert}>" . $this->_text;
        $this->_elementText = $inputText;
        
        return $this->_elementText;
    }
}

?>
