### Syncing database on post request

Feeling confused about making code to update database, this is an attempt to get hold of it using github md table drawing.

Perhaps the problem is missing leveling in try to add a smarter user interface than just demanding confirming field content by a submit.

In an input form with records navigation buttons, leaving a dirty record shall not bore the user with flush confirmation - just mimics the most obvious for somebody not knowing anything about levels of 'not real done yet' (as opposed to what we all know in the paradigm: 'save as' for files)

This is something implemented in php. php has magick global $_POST. It is writable and can thereby be used as object for information sharing - the choice is made that there are no need for further encapsulation.

One page, dbedit is involved - eg. its a one page cycle where the sending page has a html form action attribute of the sending form. A global state of page id and section id is the context for the list of records of a page section.

### Event

|event|action
|-------------|------------------------------------
|Up           |move current record up and make it current          
|Undo         |set fields back to value before last request 
|Add          |add new record 
|Send         |send change to current record
|Remove       |remove current record 
|Deletepicture|delete picture in database and file 
|First        |goto first record 
|Next         |goto next record
|Prev         |goto previous record 
|Last         |goto last record 


### Basic cycle

$_POST has keys with same name as table fields in a database table. The values of those $_POST items updates the database table. The $_POST values becomes value attributes of $_POST key named input tags of the form that facilitates the next request. The 'send' event does nothing else than that

Following characterize the basic cycle

- same record keeps being the current for editing
- there is no data hidden as event involved - eg. no upload or deletede picture
- no record existence (no add,remove)
- no record list reorder


### Extended cycle

Two tranformation is done on $_POST - one before updating the database and one before making input tags. The steps can be done for changing $_POST, some sideeffect or both.
A lot of repeated code would appear if we just makes methods. We try to sacrifice the ability for those methods having business logic, by having the events being 2 lists af functionpointers - indect as values of two arrays that has submit names as keys. 

The cycles are called prepost and postpost

### prepost methods invoked

One pseudo event: 'init' appends when the page is called first time and thereby $_POST has no items.


|event|methods
|-------------|---------
|init         |init
|Up           |nothing
|Undo         |nothing
|Add          |nothing
|Send         |nothing
|Remove       |nothing
|Deletepicture|deletepicture
|First        |nothing
|Next         |nothing
|Prev         |nothing
|Last         |nothing

### postpost methods invoked

|event|methods
|-------------|------------------------------------
|init         |N/A
|Up           |swapcurprev:readDB
|Undo         |readDB
|Add          |writeDB:addAndGotolast
|Send         |writeDB
|Remove       |removeCurrent;readDB
|Deletepicture|nothing
|First        |writeDB:idposnul;readDB
|Next         |writeDB:idposplus;readDB
|Prev         |writeDB:idposminus;readDB
|Last         |writeDB:idposmax;readDB

