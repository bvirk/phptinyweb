<?php
namespace bvirk\pages;
use bvirk\utilclasses\Sql;

class PageAware {
    private $pid;
    private $title;
    use PageFuncs;
    use \bvirk\utilclasses\TableClasses;

    private function setPidAndTitle() {
        global $pe;
        while (!$this->pid) {
            list($this->pid,$this->title)= Sql::select(Page,['id','title'],['name=?',[$pe[0]]])->fetch(\PDO::FETCH_NUM);
            if ($this->pid)
                break;
            Sql::exec("insert into ".Page." (`name`,`title`) values(?,?)",$pe[0],$pe[0]);
        }
        
    }

    public function pid() {
        if (is_null($this->pid))
            $this->setPidAndTitle();
        return $this->pid;
    }

    function title() {
        $this->pid();
        return $this->title;
    }
    
    function secOfName($secname) {
        foreach ($this->sections() as $sec)
            if ($sec['secname'] === $secname)
                return $sec;
    }
    
    function templateNames() {
        $tNames = [];
        foreach($this->sections() as $sec)
            $tNames[] = ['name' => $this->templateName($sec['templateid']), 'secid' => $sec['secid']];
        return $tNames;
    }
    
    function templateName($templateid) {
        return Sql::select(Template,'name',['id=?',[$templateid]])->fetch(\PDO::FETCH_NUM)[0];
    }

    /**
     * returns array with keys:
     *  'secid'
     *  'secname'
     *  'sectitle'
     */
    function sections() : array {
    global $pe;
    return Sql::select(SiteJoinPageJoinSec,[ 'sec.id as secid','sec.name as secname','sec.title as sectitle','templateid']
                       ,['page.name=? order by sec.id',[$pe[0]]],true)->fetchAll();
    }
    
    /**
     * returns array with keys:
     *  'siteid'
     *  'content'
     *  'pic'
     */
    function recordsOfSections($recId) : array {
        global $pe;
        if ($recId)
            return Sql::select(SiteJoinPageJoinSec
                           ,['site.id as siteid'
                           ,'content'
                           ,'pic'],['page.name=? and sec.id=? order by site.id',[$pe[0],$recId]])->fetchAll();
        return [];
    }
    
    /****
     * creates, in its absence, css file and returns its filename
     */
     
    function cssfiles($createLatest=false) {
        yield $this->cssFile(['secname' => '','secid'=>false],0,true,$createLatest);
        $addPageSelector=true;
        $secNum=1;
        foreach ($this->sections() as $sec) {
            if ($this->templateName($sec['templateid'])) {
                yield $this->cssFile($sec,$secNum++,$addPageSelector,$createLatest);
                $addPageSelector=false;
            }
        }
    }
        
    function cssFile($sec,$secNum,$addPageSelector,$createLatest) {
        global $pe;
        log("cssfile:sec: ",$sec);
        $secNumDigits = $secNum ? sprintf('%02d',$secNum) : '';
        $cssFileTemplate=$sec['secid'] ? $this->templateName($sec['templateid']):'';
        $cssFile = "css/$pe[0]_$secNumDigits$cssFileTemplate.css";
        if (file_exists($cssFile) && !$createLatest)
            return $cssFile;
        $lines = $addPageSelector ? "#page".$this->pid()." {\n".$this->pageCSSDefaults() : "";
        
        $selector = [
             'sec' => $sec['secid'] ? ".sec-".$sec['secid'].", " : ''
            ,'img' => ''
            ,'imgtext' => ''
            ,'imgpane' => ''
            ,'text' => ''];
        
        
        foreach ($this->recordsOfSections($sec['secid']) as $rec) {
            if ($rec['pic'] ?? false) {
                            $selector['img'] .= ".img-".$rec['siteid'].", ";
                            $selector['imgtext'] .= ".imgtext-".$rec['siteid'].", ";;
                            $selector['imgpane'] .= ".imgpane-".$rec['siteid'].", ";;
            } else
                $selector['text'] .= ".text-".$rec['siteid'].", ";
        }
        foreach ($selector as $skey => $sel) {
            if (strlen($sel))
                $lines .=   substr($sel,0,-2)." {\n"
                       .[$this,$skey.'CSSDefaults']()."\n";
        }
       if (!file_exists($cssFile))
           file_put_contents($cssFile,$lines);
       elseif ($createLatest)   
           file_put_contents("css/$pe[0]_{$cssFileTemplate}latest.css");
       return $cssFile;
    }

    function imgpaneCSSDefaults() {
        return <<<EOS
    border: 1px solid red;
}

EOS;
    }
    
    
    function imgtextCSSDefaults() {
        return <<<EOS
    font-size: 1.1em;
}

EOS;
    }

    
    function secCSSDefaults() {
        return <<<EOS
    font-size: 1.17em;
    margin-top: 1em;
    margin-bottom: 1em;
    font-weight: bold;
}

EOS;
    }
    
    function pageCSSDefaults() {
        return <<<EOS
    font-size: 2em;
    margin-top: 0.67em;
    margin-bottom: 0.67em;
    font-weight: bold;
}

EOS;
    }

    
    function imgCSSDefaults() {
    return <<<EOS
    border: 1px solid blue;
}

EOS;
    }
    
    function textCSSDefaults() {
    return <<<EOS
    display: block;
    margin-top: 1em;
    margin-bottom: 1em;
    margin-left: 0;
    margin-right: 0;
}

EOS;
    }

}


function defaultBody() {
    global $pe,$page;
    $pid=$page->pid();
    nodes("div",$page->title(),"id='page$pid'");
    foreach ($page->sections() as $sec) {
        nodes("div",$sec['sectitle'],"class='sec-".$sec['secid']."'");
        foreach ($page->recordsOfSections($sec['secid']) as $rec) {
            if ($rec['pic'] ?? false)
                nodes("div",node
                            ("div",["img",null,'src="/img/pages/'.$rec['pic'].'" width="150"'],'class="img-'.$rec['siteid'].'"')
                            ("div",$rec['content'],'class="imgtext-'.$rec['siteid'].'"')
                            ,'class="imgpane-'.$rec['siteid'].'"');
            else
                nodes("p",$rec['content'],"class='text-".$rec['siteid']."'");
        }
    }
}

