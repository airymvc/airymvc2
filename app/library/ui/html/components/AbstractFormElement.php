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

/**
 * Description of abstractFormElement
 *
 * @author Hung-Fu Aaron Chang
 */
class AbstractFormElement extends UIComponent{
    //put your code here
    protected $_attributes = array();
    protected $_elementText;
    protected $_decoration = null;
   
    public function setId($id)
    {
        $this->_attributes['id'] = $id;
    }
    public function getId()
    {
        return $this->_attributes['id'];
    }
    
    public function setName($name)
    {
        $this->_attributes['name'] = $name;
    }
    public function getName()
    {
        return $this->_attributes['name'];
    }
    
    public function setValue($value)
    {
        $this->_attributes['value'] = $value;
    }
    
    public function setDecoration($decoration) {
    	$this->_decoration = $decoration;
    }

    public function getDecoration() {
    	return $this->_decoration;
    }    
    
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
    /**
     * Element Decoration:
     * array('{elementId}' => array('{openHtml}', '{closeHtml}'))
     * 
     * Example of Element Decoration:
     * array('elementId'   => array('<div class="class1">', '<div>'))
     */
    protected function renderElements()
    {   
        $insert = "";
        foreach ($this->_attributes as $key => $value)
        {
            $insert .= sprintf(" %s='%s'", $key, $value);
        }
        $inputText = "<input{$insert}>";
        $openHtml  = "";
        $closeHtml = "";
        if (!is_null($this->_decoration)) {
        	$decoration = $this->_decoration[$this->getId()];
        	$openHtml  = $decoration[0];
        	$closeHtml = $decoration[1];
        }
        $this->_elementText = $openHtml . $inputText . $closeHtml;       
    }
    
    public function render()
    {
        $this->renderElements();
        return $this->_elementText;
    }
}

?>