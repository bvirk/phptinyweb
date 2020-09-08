<?php
namespace bvirk\pages;

use bvirk\utilclasses\Node as n;
use bvirk\utilclasses\CallDelegations as d;

class n extends PageAware {
    
    public function out() {
        global $pe;
        //Utils::reqLog();
                
        d::delegateByGenerator('htmlheadbody',[$this->title(),false],funcName('defaultBody'));
    }
}
