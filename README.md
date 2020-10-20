## phptinyweb  - simple php framework

... was a try to invent a framework prior haven seen any of those who rules. Having studied cakephp and codeignitor, it is not worth the effort doing anything besides listing those php technics used.

- Tables are classes -join using name mangling
- Table classes contains the sql to create them - fixed builtin migration you can say
- Defines for call to static table class makes tables apear as constants
- Tableclasses methods part of page class using traits
- Grouping categories of global functions in files loaded as empty traits 
- Modest use of global variables
- Recursive node tree constructon, using generator functions and magic \_\_invoke to build 'siblings'
- Function call for node subtree with implicit 'closing tags' of caller using generator function
- Call delegation, call be litteral method/function name and parameter detection
- Form that mimics record in a recordset of a table
- Logging and page that shows/clears log
- Using plain text output for easy testing of small snippets
- Lookup functions and classes in https://php.net using relocation
- Embed prepeared statement PDO in utility functions
    - returns pdo object
        - select
        - inUp (inserts or updates - like property)
    - returns count
        - count
        - tablesLike
    - logging ip,timestamp,userAgent of request

---

#old stuff
### Descriptions in:

- [some php.ini, ssh,ftp](doc/someUbuntuSetup.md)
- [basic urlrewite and page loading mechanism](doc/pageRendering.md) 


### setup after download

#### Database access

File 'utilclassses/Sql.php' contains Sql::pdo(), which includes 'connectionString.php'. This file has to be made, adding the required info.

|connectionString.php
|---
```php
<?php
// use as list($host,$db,$user,$pass) = include("connectionString.php");
return [ '??'  // database hosting url
        ,'??'  // database name
        ,'??'  // user name
        ,'??'  // password
        ];
```
#### Demo site

An empty phptinyweb has only one page: dbedit. If a valid connectString.php to an existing database is made, but no tables exists, following creates all tables (empty tables)
```
    http://domain/dbedit
```

To  show some features in an __not__ empty phptinyweb, the source has 6 pages:

- dbedit
    - input form for pages data
- func
    - rediretion to description for func on php.net
- class
    - redirect to description for class on php.net
- getpost
    - shows how get and post interacts in editing
- clrlog
    - shows and clears log
- somehardware
    - demo page with pictures
    
A MariaDB sql dump, 'mariaDBSampleExport.sql' has to be imported using phpmyadmin.   

A global array, $navLinkOn, contains the pages - an url requesting anything else redirects to first item.

|index.php
|---
```php
<?php
$navLinkOn = [
      'dbedit'          => ''
     ,'func'            => ''
     ,'class'           => '@class'
     ,'getpost'         => ''
     ,'somehardware'    => ''
     ,'clrlog'          => ''
    ];
...
```
