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

class DatePicker extends JUIComponent{
    
    private $_inputElement;
    private $_number        = 1;
    private $_isChangeMonth = "false";
    private $_isChangeYear  = "false";
    private $_dateFormat    = "yyyy-mm-dd";
    
    public function __construct($id, $class = null) {
        $this->_id = $id;
        $this->setAttribute('id', $id);
        if (!is_null($class)) {
            $this->setAttribute('class', $class);
        }
        $this->_inputElement = new TextElement($id);
    }
    
    public function setLabel($label_id, $label, $label_css = null)
    {
        $this->_inputElement->setLabel($label_id, $label, $label_css);
    }
    
    public function setValue($value) 
    {
        $this->_inputElement->setValue($value);   
    }
    
    public function setNumberOfMonth($number)
    {
        $this->_number = $number;
    }
    
    public function setIsChangeMonth($changeMonth) 
    {
        if ($changeMonth == true || $changeMonth == "true") {
            $this->_isChangeMonth = "true";
        }
    }
    
    public function setIsChangeYear($changeYear) 
    {
        if ($changeYear == true || $changeYear == "true") {
            $this->_isChangeYear = "true";
        }
    }
    
    public function setDateFormt($dateFormat)
    {
        $this->_dateFormat = $dateFormat;
    }
    
    
    public function render()
    {
        $datePickerText = $this->_inputElement->render(); 
        /**
         * Add Javascript support, follow the Jquery Tool Tabs
         */
        $datePickerText = $datePickerText . $this->appendJs($this->_id);
        $this->_elementText = $datePickerText;
		$this->attachJs();
        return $this->_elementText;
    }
    
    protected function appendJs($id){
        
        $options = ""; //sprintf("dateFormat: '%s'", $this->_dateFormat);
        if ($this->_isChangeMonth) {
            $options .= sprintf("changeMonth: %s", $this->_isChangeMonth);
        }
        if ($this->_isChangeYear) {
            $options .= sprintf(", changeYear: %s", $this->_isChangeYear);
        }
        
        $format = "<script  type='text/javascript'> $(function(){ \$('input#%s').datepicker({ %s }); }); </script>";

        return sprintf($format, $id, $options);
    }
    
}


?>
