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
 * Description of Menu
 *
 * @author Hung-Fu Aaron Chang
 */
class Menu extends JUIComponent{
	
    protected $_items;
    
    public function __construct($id, $class = null) {
        $this->_id = $id;
        $this->setAttribute('id', $id);
        if (!is_null($class)) {
            $this->setAttribute('class', $class);
        }
    }
	/**
 	 * Ex: Array(0 => an instance of MenuItem)
 	 * @param Array $menuItems
 	 */
    public function addMenuItem(Array $menuItems){
    	foreach ($menuItems as $itemId => $itemValue) {
        	$this->_items[$itemId] = $itemValue;
    	}
    }  
    
    public function getMenuItem($MenuItemId) {
    	foreach ($this->_items as $itemId => $itemValue) {
    		if ($itemId == $MenuItemId) {
    			return $itemValue;
    		}
    	}
    	return NULL;
    }
   
    
    public function render()
    {

        $menuHtml = $this->appendMenuHtml(); 
        /**
         * Add Javascript support, follow the Jquery Tool Menu
         */
        $this->_elementText = $menuHtml . $this->appendJs($this->_id);
   		$this->attachJs();
        return $this->_elementText;
    }
    
    protected function appendMenuHtml() 
    {
        $menuText = "<ul ";
        foreach ($this->_attributes as $key => $value)
        {
            $menuText = $menuText . " " . $key ."=\"".$value ."\"";
        }
        $menuText = $menuText . ">";
    	foreach ($this->_items as $itemId => $menuItem) {
    		if ($menuItem instanceof MenuItem) {
    			$menuItemHtml = $menuItem->render();
    			$menuText .= sprintf("<li>%s</li>", $menuItemHtml);    			
    		} else {
    			$menuText .= "<li></li>";     			
    		}
    	}
    	$menuText .= "</ul>";
        
        return $menuText;
    }
    
    protected function appendJs($menuId) {
        
        $menuJs = '<script type="text/javascript">'
        		 . '$(function() {'
        		 . '$("#' . $menuId .'").menu();'
        	     . '});'
        	     . '</script>';
        
        return $menuJs;
    }
	
}

