<?php
/**
 * AiryMVC Framework  -- AiryException
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license.
 *
 * It is also available at this URL: http://opensource.org/licenses/BSD-3-Clause
 * The project website URL: https://code.google.com/p/airymvc/
 *
 * @author: Hung-Fu Aaron Chang
 * 
 */


class AiryException extends Exception{
		
	public function __construct($message = '', $code = 0, Exception $previous = NULL) {
		$htmlMessage = "<b>Exception:</b><div>$message</div></br>";
		error_log($message);
		//print_r($message);
		parent::__construct($htmlMessage, $code, $previous);
	}
	
}