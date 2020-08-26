<?php
namespace bvirk\pages;

use bvirk\utilclasses\Node as n;
use bvirk\utilclasses\CallDelegations as d;
use bvirk\tables\ReqLog;
use bvirk\utilclasses\Sql;
use bvirk\utilclasses\FileManage as f;

function doit($prep,$values) {
  $prepItems = explode("?",$prep);
  $i =1;
  foreach ($values as $v)
      $prepItems[0] .= "'$v'".$prepItems[$i++];
  return $prepItems[0];
}



class Dbedit extends PageAware {
    const PAGEPATT      = "page";
    const SECTIONPATT   = "sec";
    
    public function out() {
        global $pe,$page;
        session_start();
        //log("page requestet");
        //Sql::reqlog();
        //bailOut("Post\r====",$_POST,"\$_FILES\r=====",$_FILES);
        //bailOut(sprintf('%02d',27));
        //if (count($_POST)) bailOut("Post\n====",$_POST,"\$_FILES\n=====",$_FILES);
        if ($pe[1] === null)
              $form = "tablesRowsCount";
        elseif (is_numeric($pe[2])) {
            updatePost();
            $form = "editSiteRecords";
        } else {
            $this->conditionalGotoSiteRecord();
            $form = "getPageAndSection";
        }
        d::delegateByGenerator('htmlheadbody',[$this->title(),false],[$this,$form]);
    }

    function tablesRowsCount() {
        global $page;
        $rc="";
        foreach($page->tableNames as $tbl)
            $rc .= sprintf("%s %d\n",$tbl,$page->rowCount($tbl,true));
        nodes("pre",$rc);
        nodes("hr");
        nodes("button","select page",'class="selectpage" onClick="window.location.href=\'/dbedit/selectpage\';"');
    }
    
    function getPageAndSection() {
        global $pe;
        $pageName=uniqid(self::PAGEPATT);   //prevent browser remembered form data
        $secName= uniqid(self::SECTIONPATT);
        form(node
            ("label","page",'for="inputpage"')
            ("input",null,"id='inputpage' name='$pageName' type='text' list='pages'")
            ("datalist",node(tnOptions(Page)),"id='pages'")
            ("label","section",'for="inputsection"')
            ("input",null,"id='inputsection' name='$secName' type='text' list='secs'")
            ("datalist",node(tnOptions(Sec)),"id='secs'")
            ("input",null,"type='submit' id='submit' style='display:none'")
            ("button","&#10004;",buttonAtt('send','submit'))
            ,"/$pe[0]/selectpage");
    }
    
    function editSiteRecords() {
        global $pe,$navLinkOn;
        
        nodes('table',['tr',node
            ('td',"Page $pe[1]/$pe[2]=")
            ('td',$_POST['pagename'],'class="pagetd"')
            ('td','/')
            ('td',$_POST['secname'],'class="sectd"')
            ]);
        
        form(node
            (formData())
            ("span",node(dbChangeButtons("submit"))("span","records",null),'class="dbchangebuttons"')
            ("div",node(fileChangeButtons("submit",is_null($_POST['pic'] ) || $_POST['pic'] == '')),'class="filechangebuttons"')
            (navButtons("submit"))
            ,"/$pe[0]/$pe[1]/$pe[2]","post","multipart/form-data");
        
        if (($_POST['pic'] ?? false)) {
            $thumbsDir ="/img/pages/thumbs/";
            $att="title='".$_POST['pic']."' src='"; 
            $att .= file_exists($thumbsDir.$_POST['pic']) 
                ? $thumbsDir.$_POST['pic']."'" 
                : "/img/pages/".$_POST['pic']."' width='200'";
            nodes("div",["img",null,$att],'class="imgdiv"');
        }
        //nodes("pre",var_export($_POST,true));
        //nodes("pre",var_export($navLinkOn,true));
        //nodes("pre",var_export($_FILES,true));
    
    }
    

       
    function conditionalGotoSiteRecord() {
        global $pe;
        $siteRecSel = [];
        foreach ($_POST as $name => $value)
            foreach ([self::PAGEPATT,self::SECTIONPATT] as $siteRecFKTN) {
                //log("conditionalGotoSiteRecord() \$siteRecFKTN: $siteRecFKTN");
                if (substr_compare($name, $siteRecFKTN, 0, strlen($siteRecFKTN)) === 0)
                if($value) {
                        Sql::inUp($siteRecFKTN,['name' => $value],false);
                        $siteRecSel[$siteRecFKTN] = $value;
                }
            }
        if ( count($siteRecSel) == 2) {
            $loc = "/$pe[0]/";
            foreach ($siteRecSel as $table => $name ) 
                $loc .= Sql::select($table,'id',['name=?',[$name]])->fetch(\PDO::FETCH_NUM)[0]."/";
            header("Location: ".substr($loc,0,-1));
            exit();
        }
    }
}

/**************************************************
 *
 * Generator functions for input fields retrivement
 */

function tnOptions($table) {
        foreach (Sql::select($table,'name') as $row)
            yield ["option",null,'value="'.$row['name'].'"'];
}

function formData($size=35)  {
    
    foreach ($_POST as $fldName => $fld) {
        if ($fldName !== 'content') {
            $isTitle = substr($fldName,-5) === "title";
            if ($isTitle)
                yield ["label",navLinkOff($_POST[substr($fldName,0,strlen($fldName)-5)."name"]),"for='for$fldName'"];
            yield ["input",null,invts($isTitle ? "for$fldName" : null, $fldName, $fld,$isTitle ? 'text' : 'hidden',$isTitle ? $size : null)];
        }
    }
    yield ["input",null,invts('submit','submit','','submit',null).' style="display:none"'];
    yield ["button","&#10004;",buttonAtt('send','submit')];
    yield ["textarea",$_POST['content'],inmrc('content','content',3000,6,'contenttextarea')];
}

function navLinkOff($label) {
    global $navLinkOn;
    return isset($navLinkOn[$label]) ? "<a href='/$label/defaultDBPage'>$label</a>" : $label;
}





/**********************************
 *
 * Html input tag attribute helpers
 *
 */


function inmrc($id,$name,$rows,$maxl,$class) {
    return invtsmrc($id,$name,null,null,null,$rows,$maxl,$class);
}
    
function invts($id,$name,$value,$type,$size) {
    return invtsmrc($id,$name,$value,$type,$size,null,null,null);
}

function invtsmrc($id,$name,$value,$type,$size,$maxlength,$rows,$class) {
    $att="";
    foreach (get_defined_vars() as $k => $v)
        if ($v !== null && $k !== 'att') 
            $att .= "$k=". ($v !== '' ? "'$v' " : "'' ") ; 
    return $att;
}







/******************************************************************
 *
 *  POST updata and database read write
 *
 *  done before any html output - redirects an deleting last record 
 *
 */

 function updatePost() { 
    global $page,$pe;
    if (count($_POST)) {
        log("updatePost",$_POST);
        // double request on file upload workaround
        if (!isset($_POST['submit'])) {
            writeDB();
            return;
        }
        foreach ([
             'up'           => ['swapCurPrev','readDB']
            ,'undo'         => ['readDB']
            ,'add'          => ['writeDB','addAndGotolast']
            ,'send'         => ['writeDB']
            ,'remove'       => ['removeCurrent','readDB']
            ,'deletepicture'=> ['deletepicture']
            ,'send'         => ['writeDB']
            ,'first'        => ['writeDB','idPosFirst','readDB']
            ,'next'         => ['writeDB','idPosNext','readDB']
            ,'prev'         => ['writeDB','idPosPrev','readDB']
            ,'last'         => ['writeDB','idPosLast','readDB']] as $sm => $funcs) 
            
            if ($sm === $_POST['submit']) 
                foreach ($funcs as $func)
                    funcName($func)();
    } else
        init();
}
function listidpos() {
    log("listidpos()");
    $siteIdList = explode("+",$_POST['siteIdList']);
    $curIdPos = $_POST['curIdPos'];
    return [$siteIdList,$siteIdList[$curIdPos],$curIdPos];
}
 
function writeDB() {
    global $pe,$page;
    /**
     * allowed buttons for $_POST ['pic'] being right
     * 
     * -send
     * -first,laste,prev,next
     *
     */
    if (($_FILES['uploadedFile']['error']  ?? 'x') === 0) {
            exitOnFirstFileupload();
            ensureUploadedFile($_POST['pic']);  //which changes $_POST['pic']
    }    
    list($siteIdList,$siteId,$curIdPos) = listIdPos();
    $pic= $_POST['pic'] ? $_POST['pic'] : null; 
    log("writeDB()");
    Sql::inUp(Site,['id'=> $siteId, 'content' => trim($_POST['content']), 'pic' => $pic],["pageid=? and secid=?",[$pe[1],$pe[2]]]);
    Sql::inUp(Page,['id'=> $pe[1], 'title' => $_POST['pagetitle']]);
    Sql::inUp(Sec,['id'=> $pe[2], 'title' => $_POST['sectitle']]);
    
}
    
function idPosFirst() {
    idGotoPos(0);
}
function idPosLast() {
    idGotoPos(2);
}
function idPosNext() {
    idGotoPos(1);
}
function idPosPrev() {
    idGotoPos(-1);
}

function idGotoPos($offsetAddend) {
    list($siteIdList,$siteId,$curIdPos) = listIdPos();
    if (!$offsetAddend)
        $offsetAddend= -$curIdPos;
    $_POST['curIdPos'] = $offsetAddend == 2 ? count($siteIdList)-1 : $curIdPos+$offsetAddend;
}

function readDb() {
    global $page;
    list($siteIdList,$siteId,$curIdPos) = listIdPos();
    foreach (Sql::select(SiteJoinPageJoinSec,
        [   'content'
            ,'pic'
            ,'page.title as pagetitle'
            ,'sec.title as sectitle'],['site.id=?',[$siteId]])->fetch() as $fldName => $fld)
        $_POST[$fldName] = $fld;
    log("readDB",$_POST);
}

function deletepicture() {
    if (($_POST['pic'] ?? false)) {
        if (Sql::count(Site,['pic=?',[$_POST['pic']]]) < 2)
            f::deleteFileNAndThumb("img/pages/".$_POST['pic']);
        $_POST['pic']=null;
    }
}

function removeCurrent() {
    global $pe;
    list($siteIdList,$siteId,$curIdPos) = listIdPos();
    deletepicture(); 
    log("removeCurrent()");
    $secId = Sql::select(Site,'secid',['id=?',[$siteId]])->fetch(\PDO::FETCH_NUM)[0];
    Sql::exec("delete from ".Site." where id=?",$siteId);
    if (count($siteIdList) == 1) {
        if (!Sql::count(Site,["secid=?",[$secId]]))
            Sql::exec("delete from ".Sec." where name=?",$_POST['secname']);
        $oldCss='css/'.$_POST['pagename']-'_'.$_POST['secname'].'.css';
        $obsCss='css/obs'.$_POST['pagename']-'_'.$_POST['secname'].'.css';
        log("renaming $oldCss $obsCss");
        rename($oldCss,$obsCss);
        header("Location: /$pe[0]/selectpage");
        exit();
    }
    unset($siteIdList[$curIdPos]);
    if ($curIdPos == count($siteIdList))
        $_POST['curIdPos'] = $curIdPos-1;
    $_POST['siteIdList'] = implode("+",$siteIdList);
}

function addAndGotolast() {
    global $pe,$page;
    $content = uniqid();
    log("addAndGotolast()");
    Sql::exec('insert into '.Site.'(`content`,`pageid`,`secid`) values(?,?,?)'
                                ,$content,$pe[1],$pe[2]);
    
    $newSiteId = Sql::select(Site,'id',['content=?',[$content]])->fetch(\PDO::FETCH_NUM)[0];
    //$page->dmsg("added id=$newSiteId for content=$content");
    list($siteIdList,$siteId,$curIdPos) = listIdPos();
    $siteIdList[] = $newSiteId;
    $_POST['siteIdList'] = implode("+",$siteIdList);
    $_POST['curIdPos'] = count($siteIdList)-1;
    $_POST['content'] = $content;
    $_POST['pic'] = null;
    
}

function swapCurPrev() {
    global $page;
    log("subittedUp");
    list($siteIdList,$curId,$curIdPos) = listIdPos();
    $swapId = $siteIdList[$curIdPos-1];
    log("swapCurPrev()");
    Sql::inUp(Site,['id' => $swapId,'id ' => 0]);         // swap saved as 0 
    Sql::inUp(Site,['id' => $curId ,'id ' => $swapId]);   // id saved as swap
    Sql::inUp(Site,['id' => 0      ,'id ' => $curId]);       // 0 saved as id
    $_POST['curIdPos'] = $curIdPos-1;
}


function init() {
    global $pe;
    log("init(");
    if(!Sql::count(Site,['pageid=? and secid=?',[$pe[1],$pe[2]]]))
        Sql::exec('insert into '.Site.'(`content`,`pageid`,`secid`) values(?,?,?)','new record',$pe[1],$pe[2]);
    $siteIdList = [];
    list($pe[2],$editId) = array_merge(explode("e",$pe[2]),[0]);
    $idPos=0;
    foreach (Sql::select(SiteJoinPageJoinSec,
        [   'site.id as siteid'
            ,'content'
            ,'pic'
            ,'page.name as pagename'
            ,'page.title as pagetitle'
            ,'sec.name as secname'
            ,'sec.title as sectitle']
        ,['pageid=? and secid=? order by site.id',[$pe[1],$pe[2]]]) as $row) {
        if (!count($siteIdList)) {
            $_POST = $row;
            $_POST['curIdPos']=0;
        }
        $siteIdList[] = $row['siteid'];
        if ($row['siteid'] == $editId) { //reassignment
            $_POST = $row;
            $_POST['curIdPos'] = $idPos;
        }
        $idPos++;
    }
    $_POST['siteIdList'] = implode("+",$siteIdList);
}  // end else if count($_POST






function exitOnFirstFileupload(int $withinSecounds=2) {
    if ($_SESSION["uploadfile"] === $_FILES['uploadedFile']['name'] && time() - $_SESSION["uploadtime"] <= $withinSecounds)
        return;
    $_SESSION["uploadfile"] = $_FILES['uploadedFile']['name'];
    $_SESSION["uploadtime"] = time();
    exit();
}

function ensureUploadedFile($oldFile) {
    $name = $_FILES['uploadedFile']['name'];
    $ext = strtolower(preg_replace('/^.+\./','',$name));
    $bareFileName = substr($name,0,-strlen($ext)-1);
    $dest = f::nextFree("img/pages/".preg_replace('/[^a-zA-Z0-9]/','',$bareFileName),$ext);
    if (move_uploaded_file($_FILES['uploadedFile']['tmp_name'], $dest)) {
        if ($oldFile)
            f::deleteFileNAndThumb("img/pages/$oldFile");
        $_POST['pic'] = preg_replace('#^.+/#','',$dest);
    }
}



