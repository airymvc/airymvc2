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

require_once '../../components/AbstractFormElement.phpractFormElement.php';
/**
 * Description of abstractButton
 *
 * @author Hung-Fu Aaron Chang
 */
class ButtonElement extends AbstractFormElement{
    //put your code here
    protected $_type  = InputType::BUTTON;
    
    public function __construct($id, $label = null)
    {
        $this->setId($id);
        $this->setAttribute("type", InputType::BUTTON);
        if (!is_null($label)) {
            $this->setAttribute("value", $label);
        }
    }
}

?>
