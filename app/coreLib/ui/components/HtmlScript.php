<?php

/**
 * Description of htmlScript
 *
 * @author Hung-Fu Aaron Chang
 */
class HtmlScript extends UIComponent{
    //put your code here
    protected $_html;
    private $_id;
    
    public function setId($id)
    {
        $this->_id = $id;
    }
    public function getId()
    {
        return $this->_id;
    }
    
    
    public function setScript($html, $id = null)
    {
        $this->_html =$html;
        $this->setId($id);
    }
    
    protected function renderElements()
    {
        $this->_html;      
    }
    
    public function render()
    {
        $this->renderElements();
        return $this->_html;
    }
}

?>
