<?php
namespace bvirk\pages;

use bvirk\utilclasses\Node as n;
use bvirk\utilclasses\CallDelegations as d;
use bvirk\utilclasses\Form;

class Getpost extends PageAware {
    use PageFuncs;
    use \bvirk\utilclasses\TableClasses;
    public $pageData = [
       'debug' => "<!--\n"
       ];
    
    public function out() {
        global $pe;
        //bailOut(); 
        d::delegateByGenerator('htmlheadbody',[$this->title(),true],[$this,'body']);
    }
    
    function body() {
        global $pe;
        $submitId = "submit1";
        nodes("h3","Get Request");
        form(node
            ("input",null,"id='$submitId' name='pe0' type='submit' style='display:none'")
            ("select",node($this->options("page",$pe[1])),"name='pe1' onchange='reqOnId(\"$submitId\",\"$pe[0]\");'")
            ("select",node($this->options("section",$pe[2])),"name='pe2' onchange='reqOnId(\"$submitId\",\"$pe[0]\");'")
            ,"/","get");
        nodes("pre","\$_GET variables\n".var_export($_GET,true));
        
        $submitId = "submit2";
        nodes("h3","Post request"); 
        form(node
            ("input",null,"id='$submitId' name='pe0' type='submit' style='display:none'")
            ("select",node($this->options("page",$pe[1])),"name='pe1' onchange='reqOnId(\"$submitId\",\"$pe[0]\");'")
            ("select",node($this->options("section",$pe[2])),"name='pe2' onchange='reqOnId(\"$submitId\",\"$pe[0]\");'")
            ,preg_replace('#/+$#','',"/$pe[0]/$pe[1]/$pe[2]"),"post");
        nodes("pre","\$_POST variables\n".var_export($_POST,true));
    }
    
    function options($name,$selNum) {
        foreach (range(1,4) as $num)
            yield ["option","$name $num","value='$num'".($num==$selNum ? " selected" : '')];
    }
    
}

    

