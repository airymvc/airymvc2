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

class DialogElement extends JUIComponent{
    
	private $_title;
	private $_message;
	
    
    public function __construct($id, $class = null) {
        $this->_id = $id;
        $this->setAttribute('id', $id);
        if (!is_null($class)) {
            $this->setAttribute('class', $class);
        }
    }
    
    public function setTitle($title)
    {
        $this->_title = $title;
        $this->setAttribute('title', $title);
    }
    
    public function setMessage($message) 
    {
        $this->_message= $message;   
    }
    
    
    public function render()
    {
        $dialogDiv = $this->composeDiv(); 
        $dialogText = $dialogDiv . $this->appendJs($this->_id);
        $this->_elementText = $dialogText;
        $this->attachJs();
        return $this->_elementText;
    }
    
    protected function composeDiv() {
    	$keyValue = "";
    	foreach ($this->_attributes as $key => $value) {
    		$keyValue .= " {$key}=\"{$value}\"";
    	}
      
    	$divText = '<div {$keyValue}>'
    	         . $this->_message
    	         . '</div>';
    }
    
    protected function appendJs($id){  
    	   
        $format = "<script  type='text/javascript'> $(function(){ \$('#%s').dialog(); }); </script>";
        return sprintf($format, $id);
    }
    
}


?>
