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
 * @author Hung-Fu Aaron Chang
 */

class AbstractForm extends UIComponent{

    protected $_attributes = array();
    protected $_elements = array();
    protected $_formText;
    protected $_formDecoration;
    
    
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
        if ($element instanceof HtmlScript) {
            if (is_null($element->getId())) {
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
        foreach ($this->_elements as $key => $element) {
            if ($element instanceof AbstractFormElement || $element instanceof FieldElement) {
                if ($element->getId() == $id) {
                    return $this->_elements[$key];
                }
            }
        }    
        return null;
    }
    
    public function getElementByName($name)
    {
        foreach ($this->_elements as $key => $element) {
            if ($element instanceof AbstractFormElement || $element instanceof FieldElement) {
                if ($element->getName() == $name) {
                    return $this->_elements[$key];
                }
            }
        }    
        return null;       
    }   

    public function setDecoration($formDecoration) {
    	$this->_formDecoration = $formDecoration;
    }
    
    public function getDecoration() {
    	return $this->_formDecoration;
    }
    
    public function setElements($elements)
    {
        $this->_elements = $elements;
    }
    /**
     * Form Decoration example:
     * 
     * array(formId      => array('<div class="class_selector">', '</div>'),
     *       elementId1  => array('<div class="elememtClass1">', '</div>'),
     *       elementId2  => array('<div class="elememtClass2">', '</div>'),
     *       ...
     *       {elementId} => array('{open_html}, {close_html})
     *      );
     *      
     * This render the form
     */
    public function render()
    {
    	$formId = null;
        $formOpenText = "<form";
        foreach ($this->_attributes as $key => $value)
        {
            $formOpenText .= " " . $key ."=\"".$value ."\"";
            if ($key == 'id') {
            	$formId = $value;
            }
        }
        $formOpenText = $formOpenText . ">";
        
        /**
         * Render the form elements here 
         */
        $elementTexts = array();
        $elementHtml = "";
        foreach ($this->_elements as $key => $element)
        {
            $elementTexts[$element->getId()] = $element->render();
            $elementHtml .= $element->render();
        }
        
        $formCloseText = "</form>";
        
        $openHtml  = $formOpenText;
        $closeHtml = $formCloseText;
        
        //Insert into formDecoration
        if (!is_null($this->_formDecoration)) {
    		 $openHtml  = $this->_formDecoration[$formId][0] . $formOpenText;
    	     $closeHtml = $formCloseText . $this->_formDecoration[$formId][1];
    	     
    		 //prepare for elements inside the form
    		 foreach ($elementTexts as $elementId => $elementText) {
    		 	      $elementOpenHtml  = (isset($this->_formDecoration[$elementId][0])) ? $this->_formDecoration[$elementId][0] : "";
    		 	      $elementCloseHtml = (isset($this->_formDecoration[$elementId][1])) ? $this->_formDecoration[$elementId][1] : "";
    				  $elementHtml = $elementOpenHtml 
    			    	           . $elementText
    			        	       . $elementCloseHtml;
    		 }
    	} 
        
        $this->_formText = $openHtml . $elementHtml . $closeHtml;
        
        return $this->_formText;
    }    
}

?>
