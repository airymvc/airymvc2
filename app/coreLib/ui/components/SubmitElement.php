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
 *
 */

require_once 'AbstractFormElement.php';
/**
 * Description of submitElement
 *
 * @author Hung-Fu Aaron Chang
 */
class SubmitElement extends AbstractFormElement{
    //put your code here
    protected $_type  = InputType::SUBMIT;
    
    public function __construct($id, $label = null)
    {
        $this->setId($id);
        $this->setAttribute("type", InputType::SUBMIT);
        if (!is_null($label)) {
            $this->setAttribute("value", $label);
        }
    }
}

?>
