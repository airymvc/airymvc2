<?php

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
