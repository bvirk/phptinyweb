<?php
namespace bvirk\pages;

class fromclass {
    
    public function out() {
    	global $pe;
        if ($pe[1]) {
            header("Location: https://www.php.net/manual/en/class.".strtolower($pe[1]).".php");
            exit();
        } else {
            header("Content-Type: text/plain;charset=UTF-8");
            echo "syntex: ".$pe[0]."/php-class";
        }
    }
}