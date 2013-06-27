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

/**
 * Description of ParamValidation
 *
 * @author Hung-Fu Aaron Chang
 */
class ParamsValidation {
    //put your code here
    private $_fields;
    private $_respones;
    
    function __construct() {
        $this->_fields = array();
        $this->_respones = array();
    }
    
    public function setValidator($name, $validator) {
        $this->_fields[$name][] = $validator; 
    }
    /**
     * @param array $params: key-value array as GET or POST results ($_GET or $_POST)
     * @return string: error message 
     */
    public function validate($params) {
        foreach ($params as $key => $value) {
            $vals = $this->_fields[$key];
            foreach ($vals as $validator) {
                $this->_respones[$key][] = $validator->isValid($value);
            }
        }
        return $this->_respones;
    }
}

?>
