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

class AutoCompleteTextField extends JUIComponent{
    
    private $_inputElement;
    private $_selections;

    
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
    
    public function setSelections($selections)
    {
        $this->_selections = $selections;
    }
    
    public function render()
    {
        $autoCompleteElement = $this->_inputElement->render(); 
        /**
         * Add Javascript support, follow the Jquery Tool Tabs
         */
        $autoCompleteElement = $autoCompleteElement . $this->appendJs($this->_id);
        $this->_elementText = $autoCompleteElement;
		$this->attachJs();
        return $this->_elementText;
    }
    
    protected function appendJs($id){
    	$sourceStr = "var availableSelections = [";
    	$cn = 0;
    	foreach ($this->_selections as $word) {
    		if ($cn != count($this->_selections) - 1) {
    			$sourceStr .= "\"{$word}\",";
    		} else {
    			$sourceStr .= "\"{$word}\"";
    		}
    		$cn++;
    	}
        $sourceStr .= "];"; 
        $format = "<script type='text/javascript'> $(function(){ %s \$('input#%s').autocomplete({ source: availableSelections }); }); </script>";

        return sprintf($format, $sourceStr, $id);
    }
    
}

?>