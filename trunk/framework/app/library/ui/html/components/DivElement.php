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

class DivElement extends AbstractFormElement{

    protected $_innerHtml;
    private $_id;
    
    public function __construct($id)
    {
        $this->setId($id);
    }
        
    public function setHtmlValue($innerHtml)
    {
        $this->_innerHtml = $innerHtml;
    }
    
    public function getHtmlValue() 
    {
        return $this->_innerHtml;	
    }
    
    //override the method
    protected function renderElements()
    {
    	$insert = "";
        foreach ($this->_attributes as $key => $value)
        {
            $insert = sprintf(" %s='%s'", $key, $value);
        }
        $inputText = "<div{$insert}>";
        
        $this->_elementText = $inputText . $this->_innerHtml . '</div>';       
    }
    
}

