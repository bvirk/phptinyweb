<?php
namespace bvirk\pages;

class Func {

    public function out() {
        global $pe;
    	if ($pe[1]) { 
            header("Location: https://www.php.net/manual/en/function.".str_replace("_","-",$pe[1]).".php");
            exit();
        }
        else {
            header("Content-Type: text/plain;charset=UTF-8");
            echo "syntax: ".$pe[0]."/php-function";
        }
    }
}