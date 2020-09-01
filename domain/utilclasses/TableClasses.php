<?php
namespace bvirk\utilclasses;

use bvirk\tables\Site;
use bvirk\tables\Page;
use bvirk\tables\Sec;
use bvirk\tables\Template;
use bvirk\tables\UserAgent;
use bvirk\tables\ReqLog;
use bvirk\utilclasses\Sql;

define("Site",Site::t());
define("Sec",Sec::t());
define("Page",Page::t());
define("UserAgent",UserAgent::t());
define("ReqLog",ReqLog::t());
define("Template",Template::t());
define("SiteJoinPage",Site::t()->innerOne(Page::t()));
define("SiteJoinPageJoinSec",Site::t()->innerOne(Page::t())->innerOne(Sec::t()));


trait TableClasses { 
    public $tableNames = [Site,Page,Sec,UserAgent,ReqLog,Template];  
    
    public function tableExists($tableName) {
        return [$this,$tableName]()->exists();
    }
    
    public function rowCount($tableName,$createIfNotExists=false) {
        $tbl = [$this,$tableName]();
        if ($createIfNotExists) 
            $tbl->create();    
        return $tbl->rowCount();
    }
    
    public function create($tableName) {
        return [$this,$tableName]()->create();
    }
    
    private function Site() { return new Site(); } 
    private function Sec() { return new Sec(); }
    private function Page() { return new Page(); }
    private function UserAgent() { return new UserAgent(); }
    private function ReqLog() { return new ReqLog();  }
    private function Template() { return new Template();  }

}

