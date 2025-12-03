<?php
    header('Content-type: text/html; charset="utf-8"');
    error_reporting(E_ALL);
    echo "KRB5CCNAME=".@$_SERVER['KRB5CCNAME']."<br>";
    putenv("KRB5CCNAME=".@$_SERVER['KRB5CCNAME']);
    $realname = explode('@', $_SERVER['PHP_AUTH_USER']);
    echo "realname=$realname<br>";
    print_r($realname);
//    print_r($_SERVER);
    phpinfo();
?>
