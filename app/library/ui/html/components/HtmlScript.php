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
