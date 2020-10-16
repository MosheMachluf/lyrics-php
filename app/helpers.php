<?php
require_once 'db_config.php';

/**
 *
 * Restore last value
 *
 * @param   string  $field_name The input name
 * @return  string
 *
 */

if(!function_exists('old')) {

    function old($field_name) {
        return $_REQUEST[$field_name] ?? '';
    }

}

if(!function_exists('csrf_token')) {

    function csrf_token() {
        $token = sha1('$//--SECRET_KEY--//$' . rand(1, 1000) . time());
        $_SESSION['token'] = $token;
        return $token;
    }

}

if(!function_exists('email_exist')) {

    function email_exist($link, $email) {
        
        $sql = "SELECT email FROM users WHERE email = '$email'";
        $result = mysqli_query($link, $sql);
        
        if($result && mysqli_num_rows($result) > 0) return true;
        else return false;
    }

}

if(!function_exists('random_str')) {

    function random_str($length = 5) {
    
    $characters = '0123456789';
    $characters .= 'abcdefghijklmnopqrstuvwxyz';
    $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    
    $max = strlen($characters) - 1;
    $randomString = '';

    for ($x = 0; $x < $length; $x++) {
        
        $randomString .= $characters[ rand(0, $max) ];
        
    }

    return $randomString;
    
    }

}