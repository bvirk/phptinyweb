<?php
namespace bvirk\utilclasses;

class HtmlGenerators {
    static function htmlheadbody($parm) {
        global $pe,$page;
        list($title,$useFocusReload) = $parm;
        
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="/css/common.css">
<?php 
    foreach($page->cssFiles() as $cssFile) 
        echo "<link rel='stylesheet' type='text/css' href='/$cssFile'>\n";
?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="/js/common.js"></script>
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

<?php } 

if (file_exists("js/$pe[0].js"))
    echo "<script src=\"/js/$pe[0].js\"></script>\n";
else

?>
<title><?php echo "$title"; ?></title>
</head>
<body>
<?php
    yield;
?>

</body>
</html>
<?php
    }
}