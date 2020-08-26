## phptinyweb  - simple php framework

### Descriptions in:

- [some php.ini, ssh,ftp](doc/someUbuntuSetup.md)
- [basic urlrewite and page loading mechanism](doc/pageRendering.md) 


### setup after download

#### Database access

File 'utilclassses/Sql.php' contains Sql::pdo(), which includes 'connectionString.php'. This file has to be made, adding the required info.

- connectionString.php
```php
<?php
// use as list($host,$db,$user,$pass) = include("connectionString.php");
return [ '??'  // database hosting url
        ,'??'  // database name
        ,'??'  // user name
        ,'??'  // password
        ];
```

|some
|---
|table
