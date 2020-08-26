## phptinyweb  - simple php framework

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

A global array, $navLinkOn, contains the pages

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
