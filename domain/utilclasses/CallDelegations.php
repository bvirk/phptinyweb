<?php
namespace bvirk\utilclasses;

class CallDelegations {

    static function delegateByGenerator($func,$parm,$callBack) {
        foreach(HtmlGenerators::$func($parm) as $i) //foreach( self::$func($parm) as $i)
            self::delegate($callBack);
    }
    
    static function delegate($func) {
        if (is_array($func)) {
            if (is_object($func[0]))
                if (count($func) === 3)
                    [$func[0],$func[1]]($func[2]);
                else
                    $func();
            else {
                //echo "calling ". $func[0] . "(" . $func[1] .")\n";
                $func[0]($func[1]);
            }
        } else 
            $func();
    }

    
}