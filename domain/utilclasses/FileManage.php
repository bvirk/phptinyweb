<?php
namespace bvirk\utilclasses;

class FileManage {
    static function nextFree($pathBaseName, $ext, $numTrailedDigits=4) {
        $postNum=-1;
        do {
            $postNum++;
            $file = $pathBaseName.($postNum ? substr("000".$postNum,-$numTrailedDigits) : '').".$ext";
        } while (file_exists($file)===true); 
        return $file;
    }
    
    static function trailingedWithDir($fileN,$trailingDir) {
        $pos =strrpos($fileN,"/");
        return substr($fileN,0,$pos+1).$trailingDir.substr($fileN,$pos+1);
    }
    
    static function deleteFileNAndThumb($filePath) : bool {
        $thumbs = self::trailingedWithDir($filePath,'thumbs/');
        if (file_exists($thumbs))
            unlink($thumbs);
        return unlink($filePath);
    }

    static function log(string ...$ma) {
        $ts = date("d H:i:s");
        foreach($ma as $m)
            file_put_contents ("log/logfile", $m === false 
                ? "$ts false\n" 
                : ($m === true 
                ? "$ts true\n" 
                : ($m === null 
                ? "$ts null\n"
                : ( $m === ''
                ? "$ts ''\n"  
                : ( is_array($m) 
                ? "$ts ".var_export($m,true)."\n" 
                : "$ts $m\n")))),FILE_APPEND);
    }
    
    static function logSql($prep,$values) {
        $prepItems = explode("?",$prep);
        $i =1;
        foreach ($values as $v)
            $prepItems[0] .= "'$v'".$prepItems[$i++];
        self::log($prepItems[0]);
    }

}