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
 * Description of JUIComponent
 *
 * @author Hung-Fu Aaron Chang
 */
class JUIComponent {
    
    protected $_id;
    protected $_attributes = array();
    protected $_elements = array();
    protected $_elementText;
    
    /**
     * attributes is a key-value structure that stores all the form attribtes 
     */
    public function setAttribute($key, $value)
    {
        $this->_attributes[$key] =  $value;
    }
    
    public function setAttributes($attributes)
    {
        $this->_attributes = $attributes;
    }
    //Use $tabLink as an unique identifer for tab container
    public function setElement($tabLink, $element)
    {
        $this->_elements[$tabLink][] =  $element;
    }
    
    public function setElements($tabLink, $elements)
    {
        $this->_elements[$tabLink] = $elements;
    }
        
    public function render(){
        return $this->_elementText;
    }
     
}

?>
