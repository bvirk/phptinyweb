<?php

namespace bvirk\tables;
use bvirk\utilclasses\Sql;

/**

field names for relation between tales t1 and t1

many to one relation

severel records in t1 related to a record in t1

t1 inner join t2 on t1.t2id = t2.id  (foreign key in t1 had the field name id prefixed name of t2)

one record in t1 relates til several record in t2

t2 inner join t1 on t2.t1id = t1.id




*/
class Join {
    private $fTable;
    private $sql="";
    private $surBeg="";
    private $surEnd="";
    protected function __construct($sql) {  $this->fTable = lcfirst(substr(strrchr($sql, "\\"), 1)); } 
    public function innerOne(object $table) {
        $this->sql .= "{$this->surEnd} inner join $table on $this->fTable.{$table}id = {$table}.id";
        $this->surBeg .= "(";
        $this->surEnd = ")";
        return $this;
    }
    public function __toString() { 
        return trim(substr($this->surBeg,0,-1)."$this->fTable $this->sql"); 
    }
    
    public function exists() {
        return is_array(Sql::tablesLike($this->fTable)); 
    }
    
    public function rowCount() {
        return Sql::count($this->fTable);
    }
    
    public function create() {
        if (!$this->exists())
            Sql::exec($this->struct);
    }
    
}