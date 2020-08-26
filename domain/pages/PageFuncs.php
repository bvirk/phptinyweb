<?php
namespace bvirk\pages;
use bvirk\utilclasses\CallDelegations;
use bvirk\utilclasses\Node;
use bvirk\utilclasses\Form;

function p(...$ma) {
    foreach($ma as $m)
        echo $m === false ? "false\n" : ($m === true ? "true\n" : ($m === null ? "null\n" : ( is_array($m) ? var_export($m,true) : "$m\n")));
}

function log(...$ma) {
    $ts = date("d H:i:s");
    foreach($ma as $m)
        file_put_contents ("log/logfile", $m === false 
            ? "$ts false\n" 
            : ($m === true 
            ? "$ts true\n" 
            : ($m === null 
            ? "$ts null\n"
            : ( $m === ''
            ? "$ts ''\n"  
            : ( is_array($m) 
            ? "$ts ".var_export($m,true)."\n" 
            : "$ts $m\n")))),FILE_APPEND);
}

function bailOut(...$ma) {
    header("Content-Type: text/plain;charset=UTF-8");
    $isNext=false;
    foreach ($ma as $m) {
        if (is_array($m) || is_object($m)) {
            if ($isNext)
                echo "-----------------------------------------------------------\n";
            var_dump($m);
        } else
            echo ($m === false ? "false" : ($m === true ? "true" : ($m === null ? "null" : ( $m === '' ? '\'\'' : $m))))."\n";
        $isNext = true;
    }
    exit();
}

function funcName($funcName) { 
    return __namespace__."\\$funcName"; 
}



/**
 * These are dulicates of static methods of Node and Form
 * needed for avoid prefixing class names  (or alias)
 * for those very much used methods.
 */

function nodes($name, $content=null, $att=null,$func=null) {
    echo "<$name $att>";
    if ($func)
        CallDelegations::delegate($func);
    unravelledNodes($content,$name);
}
    
function unravelledNodes($uknown,$name) {
    if ($uknown !== null) {
        if (is_array($uknown))     
            nodes($uknown[0],$uknown[1] ?? null,$uknown[2] ?? null);
        elseif ($uknown instanceof Node) 
            $uknown->echo();
        else
            echo $uknown;
        echo "</$name>\n";
    } else
        echo "\n";
}

function node($name=null, $content=null, $att=null) {
    return Node::node($name,$content,$att);
}
       
function form(object $nodeList, $action, $method="post", $enctype="application/x-www-form-urlencoded", $formId='formcurrent') {
    return Form::form($nodeList, $action, $method, $enctype, $formId);
}


/** GOODIES **/

/**
 * naviagtion panel
 */

 
 
 
 
 
//nodes("button","&#128257;",buttonAtt("test",$submitId)); recycle iko
function dbChangeButtons($submitId) {
    foreach ([
             ['&uarr;','up',0]
            ,['&#x2795;','add',-1]
            ,['&#9100;','undo',-1 ]
            ,['&#x2797;','remove',-1 ]] as $d) //8722
        yield ['button',$d[0],buttonAtt($d[1],$submitId).($_POST['curIdPos']==$d[2] ? " disabled":"")];
}    
function fileChangeButtons($submitId,$disabled=false) {
    yield ["input",null,"type='file' name='uploadedFile'"];
    foreach ([
            ['&#x1f5d1','&nbsp;&nbsp;','deletepicture']] as $d)
        yield ['button',$d[(int)$disabled],buttonAtt($d[2],$submitId).($disabled ? " disabled" : "")];
}    


function navButtons($submitId ) {
    $siteIdList = explode("+",$_POST['siteIdList']);
    $curIdPos = $_POST['curIdPos'];
    $maxIdPos = count($siteIdList)-1;
    foreach ([
             ['&#171;','first',0]
            ,['&#139;','prev',0]
            ,['&#155;','next',$maxIdPos ]
            ,['&#187;','last',$maxIdPos ]] as $d) {

        yield ['button',$d[0],buttonAtt($d[1],$submitId).($d[2] == $curIdPos ? " disabled" : "")];
    }
    yield ['span',($_POST['curIdPos']+1)."/".count(explode("+",$_POST['siteIdList'])).", id=".$siteIdList[$curIdPos],null];
}
function buttonAtt($title,$submitId) {
    return "title='$title' onclick='reqOnId(\"".$submitId."\",\"$title\");'";
}

 



trait PageFuncs {}
