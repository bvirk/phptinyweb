<?php

namespace bvirk\utilclasses;

use bvirk\utilclasses\Node;

class Form extends Node {
    
    /**
     * return the submitId that must be used by button to transmit a submit value
     */
    public static function form(object $nodeList, $action,$method="post",$enctype="application/x-www-form-urlencoded",$formId='formcurrent') {
        self::nodes("form",$nodeList,"action='$action' method='$method' id='$formId' enctype='$enctype'");
        return $formId;
    }
    
}
