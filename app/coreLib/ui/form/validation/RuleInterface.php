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
