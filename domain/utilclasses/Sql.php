<?php
namespace bvirk\utilclasses;
use bvirk\tables\ReqLog;
use bvirk\tables\UserAgent;
use bvirk\utilclasses\FileManage;

use \PDO as pdo;

class Sql {
    static function pdo() : object {
        static $pdoObj=null;
        if (!$pdoObj) {
            //echo "************* INITIALIZING DATABASE CONNECTION ******************\n=================================================================\n";
            $charset = 'utf8mb4';
            list($host,$db,$user,$pass) = include("servernameDB.php");
            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                ];
            $pdoObj = new PDO($dsn, $user, $pass, $options);
        }
        return $pdoObj; 
    }

    static function select($table,$fields=null,$where=null,$distinct=false) {
        $sql = "select ".($distinct ? "distinct " : "");
        if ($fields) {
            if (is_array($fields))
                foreach ($fields as $fld) {
                    $asp = explode('as',$fld);
                    $sql .= "`".str_replace('.','`.`',trim($asp[0])).(isset($asp[1]) ? '` as `'. trim($asp[1]).'`,' : '`,' );
                    //$sql .= "`".str_replace('.','`.`',$fld)."`,";
            } else
                $sql .= "$fields;";
        } else
            $sql .= "*;";
        if ($where) {
            //echo substr($sql,0,-1)." from $table where ".$where[0]."\n";
            //return self::pdoQueryNumOrNullIsInt(substr($sql,0,-1)." from $table where ".$where[0],$where[1]);
            return self::exec(substr($sql,0,-1)." from $table where ".$where[0],$where[1]);
        }
        else
            return self::pdo()->query(substr($sql,0,-1)." from `$table`");
    }
    
    static function count(string $table,$where=null) {
        if ($where) {
            $stmt = self::exec("select count(*) as count from $table where $where[0]", $where[1]);
            return $stmt->fetch()['count'];
        } 
        return self::pdo()->query("select count(*) as count from $table")->fetch()['count'];
    }

    static function tablesLike($table) {
        return self::pdo()->query("show tables like '$table'")->fetch(PDO::FETCH_NUM); 
    }

    static function inUp($table,$fields,$overWrite=true) {
        foreach (array_slice($fields,0,1,true) as  $keyName => $keyContent);
        if (!self::count($table,["`".trim($keyName)."`=?",[$keyContent]] )) {
            $sql="insert into $table(";
            $places=") values(";
            $values = [];
            foreach ($fields as $name => $value) 
                if (substr($value,0,7) !== "autoinc") {
                    $sql .= "`".trim($name)."`,";
                    $values[] = $value;
                    $places .= "?,";
                }
            return self::exec(substr($sql,0,-1).substr($places,0,-1).")",$values);
        } else { 
            if ($overWrite) {
                unset($fields[$keyName]);
                $values = [];
                $set="update $table set ";
                foreach ($fields as $name => $value) {   
                       $set .= "`".trim($name)."`=?,";
                       $values[] = $value;
                }
                $values[] = $keyContent;
                return self::exec(substr($set,0,-1)." where `".trim($keyName)."`=?",$values);
            }
        }        
    }  
    
    static function  pdoQueryNumOrNullIsInt(string $prepSql, array $values) {
        $stmt = self::pdo()->prepare($prepSql);
        foreach($values as $i => $val) { 
            $stmt->bindparam($i+1,$values[$i],is_numeric($val) || is_null($val) || is_bool($val) ? PDO::PARAM_INT : PDO::PARAM_STR_NATL);
            //echo ($i+1)." bound to ".$val."\n";
        }
        $stmt->execute();
        return $stmt;
    
    }

    static function exec(string $prepSql, ...$values) {
        if (($values[0] ?? false) && is_array($values[0]))
            $mbedValues = $values[0];
        else
            $mbedValues = $values;
        FileManage::logSql($prepSql,$mbedValues);    
        $stmt = self::pdo()->prepare($prepSql);
        foreach($mbedValues as $i => $val) { 
            $stmt->bindValue($i+1,$val,is_numeric($val) || is_null($val) || is_bool($val) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt;
    }
    
    static function reqLog() {
        //return;
        global $pe;
        $path="";
        foreach($pe as $pid)
            if($pid)
                $path .= $pid."/";
        if (!self::count(UserAgent::t(),['`name`=?',[$_SERVER['HTTP_USER_AGENT']]]))
            self::exec("insert into ".UserAgent::t()."(`name`) values(?)",$_SERVER['HTTP_USER_AGENT']);
        $id = self::select(UserAgent::t(),"id",['`name`=?',[$_SERVER['HTTP_USER_AGENT']]])->fetch(PDO::FETCH_NUM)[0];
        self::exec("Insert into ".ReqLog::t()." (`path`,`ip`,`uaid`) values(?,?,?)",$path,$_SERVER['REMOTE_ADDR'],$id);
    }
    
}    
/*** END OFF DATABASE FUNCTIONS *******************/
