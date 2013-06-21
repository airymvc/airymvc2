<?php

/**
 * Description of RuleInterface
 *   
 * This interface defines the rule interface for future implements
 * Any customized rules need to follow the interface like below.
 * Then, the customized rule can be used in the validators.
 *
 * @author Hung-Fu Aaron Chang
 */
interface RuleInterface {
    //put your code here
    public function setRule($pattern);
    
    public function validRule($input);
}

?>
