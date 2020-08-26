### Before using Laravel, Zend or ...

Some of us require a basic knowledge. Making a framework of own brew and experience how long it fits seems a way to achieve that.

### Basic framworks

deals with 4 files

- index.php
- datamixins.php
- transform.php
- htmlLines.php

##### index.php

Each page has a function, that setup the toplevel config for the request. navLinkOn shows the tree of pages. 


    require_once "datamixins.php";
    
    $navLinkOn = [
         'home' => ''
        ,'books' => 'sale'
        ,'magazines' => 'sale'
        ,'sale'=>'home'
        ,'craft'=>'home'
        ,'salecontact'=>'sale'
        ,'craftcontact'=>'craft'
        ,'serpentineroad'=>'craft'
        ];
    
    $pid = isset($_GET['pid']) && isset($navLinkOn[$_GET['pid']]) ? $_GET['pid'] : 'home';
    
        
    $pid($pid,$navLinkOn);
    
    
    function home(&$pid,&$navLinkOn) {
        $pageData = [
             'datamixin' => 'bvirk\datamixins\navPage'
            ,'role'     => 'navigation page'    
            ];
        $pageData['datamixin']($pid,$pageData,$navLinkOn);
    }
    
    function books(&$pid,&$navLinkOn) {
        $pageData = [
             'datamixin' => 'bvirk\datamixins\presentPage'
            ,'role'     => 'presentation page'    
            ];
        $pageData['datamixin']($pid,$pageData,$navLinkOn);    
    }
    
    function magazines(&$pid,&$navLinkOn) {
        $pageData = [
             'datamixin' => 'bvirk\datamixins\presentPage'
            ,'role'     => 'presentation page'    
            ];
        $pageData['datamixin']($pid,$pageData,$navLinkOn);
    }
    ... // ohther functions

    
##### datamixins.php

Here, related data is attched. The following do not justify it presents as it is a shallow shell - in practise file and data related thuff is added. following can be the first steps to be futher extended and shows the whole mechanism.  

    require_once "transform.php";
        
    use function bvirk\transform\{navPageOut,presentPageOut};
    
    
    function navPage(string &$pid, array &$pageData, array &$navLinkOn) {
        navPageOut($pid,$pageData, $navLinkOn);
    }
            
    function presentPage(string &$pid, array &$pageData, array &$navLinkOn) {
        presentPageOut($pid,$pageData, $navLinkOn);
        
    }


##### transform.php

Transforms data to a layout adapted form. Looping occurs to explode array contents to tables or other viewable forms. Packed data is tranformed or exploded to be useable in html presentation. Final step in transformation is calling htmlLines functions to output content.

    namespace bvirk\transform;
    
    require_once "htmlLines.php";

    use function bvirk\htmlLines\{preHtml,linkBrhtml,h3Tag,closeBodyHtml};
    
        
    function navPageOut(string &$pid, array &$pageData, array &$navLinkOn) {
        preHtml($pid,false);
        h3Tag( $pid . ' ' . $pageData['role']);
        foreach ($navLinkOn as $key => $navLink)
            if ($navLink === $pid)
                linkBrHtml($key,$key);
        if (strlen($navLinkOn[$pid]))
            linkBrHtml($navLinkOn[$pid],$navLinkOn[$pid],"calling page");
        closeBodyHtml();
    }
            
    function presentPageOut(string &$pid, array &$pageData, array &$navLinkOn) {
        preHtml($pid,false);
        h3Tag( $pid . ' ' . $pageData['role']);
        linkBrHtml($navLinkOn[$pid],$navLinkOn[$pid],"calling page");
        closeBodyHtml();
    }


##### htmlLines.php

php and html is mixed to achieve two things

- efficiency
- syntax highlighting in editor
    
breaking that role is just to emphasize it.

    namespace bvirk\htmlLines;
    
    function preHtml(&$title,bool $useFocusReload=true) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
    <meta charset="utf-8">
    <?php if ($useFocusReload) { ?>
    <script>
        (function() {
           var blured=false;
           window.onblur = (() => { setTimeout(() => blured=true,2000)});
           window.onfocus = (() =>  {
               if (blured) {
                   blured=false;
                   window.location.reload(true);
           }});
        })();
    </script>
    <?php } ?>
    <link rel="stylesheet" type="text/css" href="all.css">
    <title><?php echo $title; ?></title>
    </head>
    <body>
    <?php
    }
    
    function linkBrHtml(&$link,$text) {
        echo <<<EOT
    <a href="/$link">$text</a><br>
    
    EOT;
    }
    
    function linkBrHtml(&$link,$text,$h3='') {
        if ($h3)
            echo "<h3>$h3</h3>";
        echo <<<EOT
    <a href="/$link">$text</a><br>
    
    EOT;
    }

    function closeBodyHtml() {
        echo "</body></html>";
    }

