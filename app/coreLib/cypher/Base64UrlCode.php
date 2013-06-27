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
 * Description of Base64UrlCoder
 *
 * @author Hung-Fu Aaron Chang
 */
class Base64UrlCode {
    

    private static $secretKey = "XsdfGDS#$3C";

    //put your code here
    public static function  encrypt($text, $secretKey = null) {
        $secretKey = (is_null($secretKey)) ? self::$secretKey : $secretKey;
        $ctext = base64_encode($text) . $secretKey;
        $result = urlencode(base64_encode($ctext));
        return $result;
    }
    
    public static function  decrypt($code, $secretKey = null) {
        $secretKey = (is_null($secretKey)) ? self::$secretKey : $secretKey;
        $data = urldecode($code);
        $ctext = base64_decode($data);
        $endpos = strlen($ctext) - strlen($secretKey);
        $text = substr($ctext, 0, $endpos);
        $result =  base64_decode($text);
        return $result;
    }
    
}

?>
