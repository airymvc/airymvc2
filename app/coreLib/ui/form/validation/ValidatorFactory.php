<?php

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
