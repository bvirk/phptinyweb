<?php
namespace bvirk\pages;

use bvirk\utilclasses\Node as n;
use bvirk\utilclasses\CallDelegations as d;

function test() {
    global $page;
    p($page->templateNames());
}
            


class Bvirk extends PageAware {
    
    public function out() {
        global $pe;
        //bailOut(test());
        //Utils::reqLog();
        d::delegateByGenerator('htmlheadbody',[$this->title(),false],[$this,'sectionTemplates']);
    }
    public function sectionTemplates() {
        nodes("a","&#128231;","href='#' id='email' class='screenshot' rel='/img/pages/rebus.png' title='above danish rebus spells my email address'");
        nodes("a",["img",null,"src='/img/pages/linkedin.png'"],"href='https://www.linkedin.com/in/benny-andersen-725a57197/'");
        nodes("a",["img",null,"src='/img/pages/github32.png'"],"href='https://github.com/bvirk'");
        //foreach ($this->templateNames() as $secTmpl)        
        //    n::asChild([funcname($secTmpl['name']),$secTmpl],"div","class='section-".$secTmpl['name']."'");
        foreach ([
                 'elkamator'
                ,'operativ'
                ,'clearboth'
                ,'lang'
                ,'divW200H100'
                ,'divW500H400'
                ,'handson'
                ] as $sec)        
            n::asChild([funcname($sec),$sec],"div","class='section-$sec'");

    }
}

function elkamator($meName) {
?>
<div class='imgpane-<?php echo $meName;?>'>
    <div class='img-<?php echo $meName;?>'>
        <img src="/img/pages/antex.png">
    </div>
    <div class='ul-<?php echo $meName;?>'>
        <div >Elektronik amatør<br></div>
        <ul >
                 <li>komponenter
            </li><li>diagram
            </li><li>beregninger
            </li>
        </ul>
    </div>    
</div>
<?php
}


function operativ($meName) {
?>
<div class='imgpane-<?php echo $meName;?>'>
    <div class='img-<?php echo $meName;?>'>
        <img src="/img/pages/arduinoMan.png">
    </div>
    <div class="ul-<?php echo $meName;?>">
        <div >Operativ systemer<br></div>
        <ul >
                 <li>linux
            </li><li>avr libraries
            </li>
        </ul>
    </div>    
</div>
<?php
}

function clearboth($meName) {
    echo "<div class='$meName'> </div>\n";
}

function divW200H100($meName) {
    echo "<div class='$meName'> </div>\n";
}
function divW500H400($meName) {
    echo "<div class='$meName'> </div>\n";
}
                

function lang($meName) {
?>
<div class='imgpane-<?php echo $meName;?>'>
    <div class='img-<?php echo $meName;?>'>
        <img src="/img/pages/psource.png">
    </div>
    <div class="ul-<?php echo $meName;?>">
        <div >Programmingssprog<br></div>
        <ul >
                 <li>c / c++
            </li><li>java
            </li><li>php
            </li>
        </ul>
    </div>    
</div>
<?php
}

function handson($meName) {
?>
<table>
<tr><th colspan="2">Hands on</th></tr>
<tr>
    <td><ul class="<?php echo $meName;?>">
             <li> microbit
        </li><li> arduino
        </li><li> mbed.com
        </li><li> html/css/
        </li><li> jedit
        </li>
    </ul></td>
    <td><ul class="<?php echo $meName;?>">
             <li> sketchup
        </li><li> ms visio
        </li><li> ms access
        </li><li> photoshop/gimp
        </li>
    </ul></td>
</tr>    
</table>

<?php
}


function textOverPic($secTmpl) {
    global $page;
    
    extract($secTmpl);
       
    foreach ($page->recordsOfSections($secid) as $rec) {
        if ($rec['pic'] ?? false)
            nodes("div",node
                        ("div",["img",null,'src="/img/pages/'.$rec['pic'].'"'],"class='img-$name'")
                        ("div",$rec['content'],"class='imgtext-$name'")
                        ,"class='imgpane-$name'");
        else
            nodes("p",$rec['content'],"class='text-$name'");
    }
    
}



function listOverPic($secTmpl) {
    global $page;
    
    extract($secTmpl);
    
    
    foreach ($page->recordsOfSections($secid) as $rec) {
        if ($rec['pic'] ?? false) {
            $items = explode("\r\n",$rec['content']); 
            $firstItem= reset($items);
            unset($items[key($items)]);
            nodes("div",node
                        ("div",["img",null,'src="/img/pages/'.$rec['pic'].'"'],"class='img-$name'")
                        ("div",$firstItem,null)
                        ("ul",node(listElements($items)),null)
                        ,"class='imgpane-$name'");
        } else
            nodes("p",$rec['content'],"class='text-$name'");
    }
    
}



function ulList($secTmpl) {
    global $page;
    
    extract($secTmpl);
    foreach ($page->recordsOfSections($secid) as $rec) { 
        $items = explode("\r\n",$content); 
        $firstItem= reset($items);
        unset($items[key($items)]);
        nodes("div",node
                    ("div",$firstItem,null)
                    ("ul",node(listElements($items)),null)
                ,"class='ul-$name'");
    }
}

function listElements($items) {
    foreach ($items as $item) 
        yield ["li",$item,null];
}
