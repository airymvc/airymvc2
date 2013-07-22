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
 * SubMenu is attached under a specific MenuItem 
 *
 * @author Hung-Fu Aaron Chang
 */
class SubMenu {
    
	protected $_items;
    
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
    	$menuHtml = "<ul>";
    	foreach ($this->_items as $itemId => $menuItem) {
    		if ($menuItem instanceof MenuItem) {
    			$menuItemHtml = $menuItem->render();
    			$menuHtml .= sprintf("<li>%s</li>", $menuItemHtml);    			
    		} else {
    			$menuHtml .= "<li></li>";     			
    		}
    	}
    	$menuHtml .= "</ul>";
    	return $menuHtml;
    }
	
}