<?php
namespace bvirk\pages;

use bvirk\utilclasses\Node as n;
use bvirk\utilclasses\CallDelegations as d;
use bvirk\utilclasses\Sql;

class Somehardware extends PageAware {
    public $pageData = [
       'debug' => "<!--\n"
       ];
    
    public function out() {
        global $pe;
        session_start();
        //Utils::reqLog();
        //bailOut($this->sections());

                
        d::delegateByGenerator('htmlheadbody',[$this->title(),false],funcName('body'));
    }
}

function body() {
    global $page;
    $pid=$page->pid();
    nodes("div",$page->title(),"id='page$pid'");
    foreach ($page->sections() as $sec) {
        log("jumpung to function ".$sec['secname']);
        n::asChild([funcname($sec['secname']),$sec],"div",'class="allFields"');
    }
}

function network($sec) {
    eee900($sec);
}

function eee900($sec) {
    global $page;
    //return;
    nodes("div",$sec['sectitle'],"class='sec-".$sec['secid']."'");
    foreach ($page->recordsOfSections($sec['secid'] ) as $rec) {
        if ($rec['pic'] ?? false)
            nodes("div",["img",null,'src="/img/pages/'.$rec['pic'].'"'],'class="img-'.$rec['siteid'].'"');
        if ($rec['siteid'] >74)
            $content = str_replace("\n","<br>",$rec['content']);
        else
            $content = $rec['content'];
        nodes("div",$content,"class='text-".$rec['siteid']."'");
    }
}

function eee1005($sec) {
    global $page;
    //return;
    nodes("div",$sec['sectitle'],"class='sec-".$sec['secid']."'");
    foreach ($page->recordsOfSections($sec['secid'] ) as $rec) {
        log('eee1005($sec)',$page->recordsOfSections($sec['secid'] ));
        if ($rec['pic'] ?? false)
            nodes("div",["img",null,'src="/img/pages/'.$rec['pic'].'"'],'class="img-'.$rec['siteid'].'"');
        if ($rec['siteid'] >74)
            $content = str_replace("\n","<br>",$rec['content']);
        else
            $content = $rec['content'];
        nodes("div",$content,"class='text-".$rec['siteid']."'");
    }
}

function powersupl($sec) {
    global $page;
    //return;
    nodes("div",$sec['sectitle'],"class='sec-".$sec['secid']."'");
    foreach ($page->recordsOfSections($sec['secid'] ) as $rec) {
        if ($rec['pic'] ?? false)
            nodes("div",node
                        ("div",["img",null,'src="/img/pages/'.$rec['pic'].'"'],'class="img-'.$rec['siteid'].'"')
                        ("div",$rec['content'],'class="imgtext-'.$rec['siteid'].'"')
                        ,'class="imgpane-'.$rec['siteid'].'"');
        elseif($rec['siteid']<65)
            nodes("div",$rec['content'],'class="text-'.$rec['siteid'].'"');
            
    }
    n::asChild([funcname('trList'),$sec],"table","class='tablesec-".$sec['secid']."'");
}

function trList($sec) {
    global $page;
    $tdt = "th";
    foreach ($page->recordsOfSections($sec['secid']) as $rec)
        if (!$rec['pic'] && $rec['siteid'] > 64) {
            nodes("tr",node(tdList($rec['content'],$tdt)),"class='text-".$rec['siteid']."'");
            $tdt = "td";
        }
}

function tdList($tr,$tdt) {
        foreach (explode("|",$tr) as $data)
            yield [$tdt,$data,null];
}
