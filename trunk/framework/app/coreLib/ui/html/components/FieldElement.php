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

require_once ('../../components/AbstractFormElement.phpractFormElement.php');
/**
 * Description of fieldElement
 *
 * @author Hung-Fu Aaron Chang
 */
class FieldElement extends AbstractFormElement{
    //put your code here
    protected $_label;
    protected $_label_id;
    protected $_label_css;
    
    public function setLabel($label_id, $label, $label_css = null)
    {
        $this->_label     = $label;
        $this->_label_id  = $label_id;
        $this->_label_css = $label_css;
    }
    
    protected function renderElements()
    {
        $inputText = "<div id='{$this->_label_id}' class='{$this->_label_css}'>{$this->_label}</div><input";
        foreach ($this->_attributes as $key => $value)
        {
            $inputText = $inputText . " " . $key ."=\"".$value ."\"";
        }
        $inputText = $inputText . ">";
        $this->_elementText = $inputText;      
    }
    
}

?>
