## phptinyweb  - page mechanism


### .htaccess, index.php, global variables and datamodel
 
A request by url
```
http://domain/huey/dewey/louie
```
would, by use of this .htaccess file
```
RewriteEngine on
RewriteRule ^(\w+)/?$ ?pe0=$1
RewriteRule ^(\w+)/(\w+)/?$ ?pe0=$1&pe1=$2
RewriteRule ^(\w+)/(\w+)/(\w+)/?$ ?pe0=$1&pe1=$2&pe2=$3
```
result in following get parameters.

- $_GET['pe0']=huey
- $_GET['pe1']=dewey
- $_GET['pe2']=louie
    
$pe (short for path element) is a global variable.   

For relevant selecting, a  global variabel - $navLinkOn - containg what $pe[0] could be
|index.php
|---

```php
<?php
$navLinkOn = [
      'test'    => ''
     ,'func'    => ''
     ,'class'   => '@class'
     ,'getpost' => ''
    ];

if (!isset($navLinkOn[$_GET['pe0'] ?? '']))
    header("Location: /".key($navLinkOn));

$pe = [$_GET['pe0'],$_GET['pe1'] ?? null ,$_GET['pe2'] ?? null ];
```
$pe[0] is the page and each page can use $pe[1] and $pe[2] for whatever. First key in $navLinkOn is default and because each page shall recieve a true showing url path, then false or empty url is redirected to default

$pe[0]/$pe[1] also forms a data model

- pe[0] table page(id,name,title)
- pe[1] table sec(id,name,title)

The id's of page and sec is foreign keys in

- table site(id,content,pageid,secid,fileRef)

