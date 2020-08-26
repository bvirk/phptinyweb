## phptinyweb  - simple php framework

### Introduction

phptinyweb is the most rudimentary framework to layout a collection of pages. The content is MariaDB database driven.
This readme shall:

- Introduce what the framework not in itself documents.
- Rudimentary explain how code works, structured as pages with section and replicated in the framework

http://domain is used as placeholder name for domain of the actual hosting.



### .htaccess, index.php, , global variables
 
An url
```
http://domain/huey/dewey/louie
```
could, by use of this .htaccess file
```
RewriteEngine on
RewriteRule ^(\w+)/?$ ?pe0=$1
RewriteRule ^(\w+)/(\w+)/?$ ?pe0=$1&pe1=$2
RewriteRule ^(\w+)/(\w+)/(\w+)/?$ ?pe0=$1&pe1=$2&pe2=$3
```
request index.php with the following get request parameters

- $_GET['pe0']=huey
- $_GET['pe1']=dewey
- $_GET['pe2']=louie
    
$pe (short for path element) is a global variable.   

For relevant selecting, a  global variabel - $navLinkOn - containg what $pe[0] could be

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

the following 'pages' shall be a replication of pages in phptinyweb when fetched from this github project.


## page dbedit, and database access to MariaDB

Database connection demands a file, _servernameDB.php_ in document root. It has to be made manually after this scheme  
```php
<?php
// use as list($host,$db,$user,$pass) = include("servernameDB.php");
return [
    'host1' => [   // one hostings $_SERVER['SERVER_NAME']
         'mysqlhost1'
        ,'databasename1'
        ,'username1'
        ,'password1'
        ]
    'host2' => [   // another hostings $_SERVER['SERVER_NAME']
         'mysqlhost2'
        ,'databasename2'
        ,'username2'
        ,'password2'
        ]
    ][$_SERVER['SERVER_NAME']];
```
Above _servernameDB.php_, has SERVER_NAME context. Tip: bailOut($_SERVER['SERVER_NAME']) as the first statement in a page's class method out() shows the current installations $_SERVER['SERVER_NAME'].

Without arguments, page dbedit shows records count for the tables and if they not exists - _creates_ them. 

__IMPORTANT__: In a new phptinyweb, dbedit without arguments creates all tables. This also initial ensures that credentials in servernameDB.php are right - if not a lot of, for the uninitiated, php error reporting muble bumble is the only result.

## page getpost

Mimics four values for page id and section id, and shows how get request can change and post reqest keeps at same section on same page. It illustrates above mapping of querystring to rewritten url. The keeping on same section of a page for post request is the princip for editing content content and fileRef in the page where data is edited.

## page adding a page
