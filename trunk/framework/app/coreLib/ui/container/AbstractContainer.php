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


abstract class AbstractContainer extends UIComponent{
    //put your code here
    protected $_attributes = array();
    protected $_elements = array();
    
    
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
    
    public function setElement($element, $key = null)
    {
        $key = is_null($key)? count($this->_elements) : $key;
        $this->_elements[$key] =  $element;
    }
    
    public function setElements($elements)
    {
        $this->_elements = $elements;
    }
    
    public function getElements()
    {
        return $this->_elements;
    }
    
    public function getElement($key)
    {
        return $this->_elements[$key] =  $element;
    }
    
    
}

?>
