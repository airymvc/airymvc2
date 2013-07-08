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

require_once 'FieldElement.php';
require_once 'InputType.php';
/**
 * Description of passwordElement
 *
 * @author Hung-Fu Aaron Chang
 */
class PasswordElement extends FieldElement{
    //put your code here
    protected $_type = InputType::PASSWORD;
    
    public function __construct($id)
    {
        $this->setId($id);
        $this->setAttribute("type", InputType::PASSWORD);
    }
}

?>
