<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AbstractContainer
 *
 * @author Hung-Fu Aaron Chang
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
