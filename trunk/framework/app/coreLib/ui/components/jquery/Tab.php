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
 * Description of Tab
 *
 * @author Hung-Fu Aaron Chang
 */
class Tab extends JUIComponent{
    
    protected $_tabs;
    protected $_elementText;
    
    public function __construct($id, $class = null) {
        $this->_id = $id;
        $this->setAttribute('id', $id);
        if (!is_null($class)) {
            $this->setAttribute('class', $class);
        }
    }
    //$tabLink is the identifer for the tab
    //It could be a URL or #Identifer
    public function addTab($label, $tabLink, $isAjax = false){
        $this->_tabs[$tabLink] = array($label, $isAjax);
    }  
    

    
    public function render()
    {

        $tabText = $this->appendTabHtml(); 
        /**
         * Add Javascript support, follow the Jquery Tool Tabs
         */
        $tabText = $tabText . $this->appendTabJs($this->_id);        
        $this->_elementText = $tabText;
   
        return $this->_elementText;
    }
    
    protected function appendTabHtml() 
    {
        $tabText = "<div ";
        foreach ($this->_attributes as $key => $value)
        {
            $tabText = $tabText . " " . $key ."=\"".$value ."\"";
        }
        $tabText = $tabText . ">";
        $tabText = $tabText . "<ul>";
        
        foreach ($this->_tabs as $tabLink => $vars) {
            if (!$vars[1]) {
                $tabText = $tabText . "<li><a href='#" . $tabLink . "'>" . $vars[0] . "</a></li>";
            } else {
                $tabText = $tabText . "<li><a href='". $tabLink . "'>" . $vars[0] . "</a></li>";                
            }
        }
        
        $tabText = $tabText. '</ul>';
        
        foreach ($this->_tabs as $tabLink => $class) {
            $vars = $this->_tabs[$tabLink];
            //#vars[1] defines isAjax or not
            if (!$vars[1]) {
                $tabText = $tabText . "<div id='" . $tabLink . "'>";
                /**
                * Render the form elements here 
                */
                if (!is_null($this->_elements[$tabLink]) && isset($this->_elements[$tabLink])) {
                    foreach ($this->_elements[$tabLink] as $elemKey => $element)
                    {
                        $tabText = $tabText . $element->render();
                    }
                }
                $tabText = $tabText . "</div>";
            }
        }
        
        $tabText = $tabText . "</div>";
        
        return $tabText;
    }
    
    protected function appendTabJs($tabId) {
        
        $tabText = '<script type="text/javascript">'
        		 . '$(function() {'
        		 . '$("div#' . $tabId .'").tabs();'
        	     . '});'
        	     . '</script>';
        
        return $tabText;
    }
}

?>
