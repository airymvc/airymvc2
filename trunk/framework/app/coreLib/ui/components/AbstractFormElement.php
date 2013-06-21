<?php

/**
 * Description of abstractFormElement
 *
 * @author Hung-Fu Aaron Chang
 */
class AbstractFormElement extends UIComponent{
    //put your code here
    protected $_attributes = array();
    protected $_elementText;
    
   
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
    
    protected function renderElements()
    {
        $inputText = "<input";
        foreach ($this->_attributes as $key => $value)
        {
            $inputText = $inputText . " " . $key ."=\"".$value ."\"";
        }
        $inputText = $inputText . ">";
        $this->_elementText = $inputText;       
    }
    
    public function render()
    {
        $this->renderElements();
        return $this->_elementText;
    }
}

?>