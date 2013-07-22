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
 * MenuItem is used by SubMenu and Menu
 *
 * @author Hung-Fu Aaron Chang
 */

class MenuItem {
	
	private $label;
	private $link;
	private $iconCssClass;
	private $subMenu;
	
	public function __construct($label, $link, $iconCssClass = NULL, $subMenu = NULL) {
		$this->label = $label;
		$this->link  = $link;
		$this->iconCssClass = $iconCssClass;
		$this->subMenu = $subMenu;
	}
	
	public function render() {
		$html = "";
		if (is_null($this->iconCssClass)) {
			$html = sprintf('<a href="%s">%s</a>', $this->link, $this->label);
		} else {
			$html = sprintf('<a href="%s"><span class="%s"></span>%s</a>', $this->link, $this->iconCssClass, $this->label);
		}
		if (!is_null($this->subMenu) && $this->subMenu instanceof SubMenu) {
			$html .= $this->subMenu->render();
		}
		return $html;
	}
}