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
 * @author Hung-Fu Aaron Chang
 */

require_once ('AbstractForm.php');

class GetForm extends AbstractForm{
    //put your code here
        public function __construct($id) {
        $this->setAttribute('id', $id);
        $this->setAttribute('method', 'get');
    }
}

?>
