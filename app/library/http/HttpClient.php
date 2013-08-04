<?php
/*
 *   AiryMVC Framework
 *  
 *   LICENSE
 *  
 *   This source file is subject to the new BSD license.
 *  
 *   It is also available at this URL: http://opensource.org/licenses/BSD-3-Clause
 *   The project website URL: https://code.google.com/p/airymvc/
 *  
 *   @author: Hung-Fu Aaron Chang
 */


class HttpClient {
    
    /**
     * Get the data from a url
     * 
     * @param string $url
     * @param int $timeOut
     * @return string 
     */
    public function getData($url, $timeOut = 5) {
		$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeOut);

		$data = curl_exec($curl);
		curl_close($curl);
		return $data;
    }
 }
 
 
 
?>