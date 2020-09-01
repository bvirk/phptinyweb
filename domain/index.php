<?php
$navLinkOn = [
      'bvirk'       => ''
     //,'dbedit'      => ''
     ,'func'        => ''
     ,'class'       => '@class'
     ,'getpost'     => ''
     ,'somehardware'=> ''
     //,'clrlog'      => ''
    ];

if (!isset($navLinkOn[$_GET['pe0'] ?? '']))
    header("Location: /".key($navLinkOn));

$pe = [$_GET['pe0'],$_GET['pe1'] ?? null ,$_GET['pe2'] ?? null ];
    
function autoload($classN) {
    global $pe;
    $topLess = str_replace("\\","/",substr($classN,strpos($classN,"\\")+1));
    
    $autogenPages = ['defaultPage','defaultDBPage'];
    if (!file_exists("$topLess.php") && in_array($pe[1],$autogenPages)) {
        //echo " trying to create $topLess.php";
        file_put_contents("$topLess.php", $pe[1](preg_replace('#^.+/#','',$topLess)));
    }
    require_once "$topLess.php";
}
spl_autoload_register('autoload');

if (substr($navLinkOn[$pe[0]],0,1) === '@') 
    $classN = "bvirk\\pages\\From".substr($navLinkOn[$pe[0]],1);
else 
    $classN = "bvirk\\pages\\".ucfirst($pe[0]);

$page = new $classN();
$page->out();

function defaultPage($bareClassN) {
    $dl='$';
    return <<<EOT
<?php
namespace bvirk\pages;

use bvirk\utilclasses\Node as n;
use bvirk\utilclasses\CallDelegations as d;

class $bareClassN extends PageAware {
    
    public function out() {
        global {$dl}pe;
        //Utils::reqLog();
        d::delegateByGenerator('htmlheadbody',[{$dl}this->title(),false],funcName('body'));
    }
}

function body() {
    global {$dl}pe;
    nodes("h4","pages/".ucfirst({$dl}pe[0]).".php can now be edited");
}
EOT;
}



function defaultDBPage($bareClassN) {
    $dl='$';
    return <<<EOT
<?php
namespace bvirk\pages;

use bvirk\utilclasses\Node as n;
use bvirk\utilclasses\CallDelegations as d;

class $bareClassN extends PageAware {
    
    public function out() {
        global {$dl}pe;
        //Utils::reqLog();
                
        d::delegateByGenerator('htmlheadbody',[{$dl}this->title(),false],funcName('defaultBody'));
    }
}

EOT;
}
?>