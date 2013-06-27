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
 * Description of simpleForm
 *
 * @author Hung-Fu Aaron Chang
 */

class AbstractForm extends UIComponent{
    //put your code here
    protected $_attributes = array();
    protected $_elements = array();
    protected $_formText;
    
    
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
    
    public function setElement($element)
    {
        if ($element instanceof HtmlScript)
        {
            if (is_null($element->getId()))
            {
                $this->_elements[] =  $element;
            } else {
                $this->_elements[$element->getId()] =  $element;
            }
        } else {
            $this->_elements[$element->getId()] =  $element;
        }
    }
    public function getElementById($id)
    {
        foreach ($this->_elements as $key => $element)
        {
            if ($element instanceof AbstractFormElement || $element instanceof FieldElement)
            {
                if ($element->getId() == $id)
                {
                    return $this->_elements[$key];
                }
            }
        }    
        return null;
    }
    public function getElementByName($name)
    {
         foreach ($this->_elements as $key => $element)
        {
            if ($element instanceof AbstractFormElement || $element instanceof FieldElement)
            {
                if ($element->getName() == $name)
                {
                    return $this->_elements[$key];
                }
            }
        }    
        return null;       
    }    
    public function setElements($elements)
    {
        $this->_elements = $elements;
    }
    
    public function render()
    {
        $formText = "<form";
        foreach ($this->_attributes as $key => $value)
        {
            $formText = $formText . " " . $key ."=\"".$value ."\"";
        }
        $formText = $formText . ">";
        
        /**
         * Render the form elements here 
         */
        foreach ($this->_elements as $key => $element)
        {
            $formText = $formText . $element->render();
        }
        
        $formText = $formText . "</form>";
        $this->_formText = $formText;
        return $this->_formText;
    }
    
}

?>
