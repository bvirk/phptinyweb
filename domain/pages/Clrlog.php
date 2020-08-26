<?php
namespace bvirk\pages;

use bvirk\utilclasses\Node as n;
use bvirk\utilclasses\CallDelegations as d;

class Clrlog extends PageAware {
    
    public function out() {
        global $pe;
        //Utils::reqLog();
        bailOut(readlog());
        d::delegateByGenerator('htmlheadbody',[$this->title(),false],funcName('body'));
    }
}


function body() {
    global $pe;
    nodes("h4","pages/".ucfirst($pe[0]).".php can now be edited");
}

function readlog() {
    $fileName="log/logfile";
    $content = file_get_contents($fileName);
    unlink($fileName);
    return $content;
}    