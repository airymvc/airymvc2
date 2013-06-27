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
 */

/**
 * Description of ValidatorFactory
 *
 * @author Hung-Fu Aaron Chang
 */
class ValidatorFactory {
    
    public function create($validatorClassName) {
        if (class_exists($validatorClassName)) {
            return new $validatorClassName();
        }
        
        return null;
    }
}

?>
