<?php
namespace bvirk\utilclasses;

use bvirk\utilclasses\CallDelegations;

class Node {
    
    /**
     *  node(s) that contains nodes.
     *
     * Examples:
     * --------
     *
     * A tree with one child (inside one child ....
     * =======================================
     *
     * nodes("div",["a",["img",null,'src="#"'],'href="#"'],'class="outerdiv"');
     *
     *   1. each [ starts a node inside its parent. 
     *   2. the innermost has the normal position order the of parameters
     *   3. climbing back trails with attributes af matching [ to the ] that follows the attribute - ( ) for the first node
     *
     * another style - column aligning commas and make precommating an attribute sign. 
     *
     *   nodes("div",
     *               ["a",
     *                    ["img",'','src="#"']
     *                   
     *                   ,'href="#"']
     *              ,'class="outerdiv"');
     *
     * 
     *
     * A list of nodes inside a node
     * =============================
     * nodes("select",
     *             node("option","Volvo",'value="Volvo"')
     *             ("option","Saab",'value="Saab"')
     *             ("option","VW",'value="VW"')
     *             ("option","Audi",'value="Audi"')
     *               ,'id="cars"');
     *        
     * the inside list is a single Node object which items of (name, content, attributes) values  after the inital (name, content, attributes) value 
     * are appended.
     * The return values of first node makes chaining possible - if it is unconvenient to give it a value, node() can be used as first.
     * Presiding parameters after first can also be Generator Object as first parameter -the above then becomes
     *
     * nodes("select",node()(options()),'id="cars"');
     * 
     * function options() {
     *       foreach (['Saab','VW','Audi'] as $c)
     *           yield ["option",$c,"value='$c'"]
     *  }
     */
    private $nameContentAtt = [];
     
    public function __construct($name=null,$content=null,$att=null) {
        if ($name)
            $this->nameContentAtt[] = [$name,$content,$att];
    }

    static function nodes($name, $content=null, $att=null,$func=null) {
        echo "<$name $att>";
        if ($func)
            CallDelegations::delegate($func);
        self::unravelledNodes($content,$name);
    }
    
    static function unravelledNodes($uknown,$name) {
        if ($uknown !== null) {
            if (is_array($uknown))     
                self::nodes($uknown[0],$uknown[1] ?? null,$uknown[2] ?? null);
            elseif ($uknown instanceof Node) 
                $uknown->echo();
            else
                echo $uknown;
            echo "</$name>\n";
        } else
            echo "\n";
    }
    
    public function echo() { 
        foreach ($this->nameContentAtt as $nca) 
            self::nodes($nca[0],$nca[1],$nca[2]);
    }

    static function node($uknown=null, $content=null, $att=null) {
        if ($uknown instanceof \Generator) {
            list($n,$c,$a) = $uknown->current();
            $nNode = new Node($n,$c,$a);
            $uknown->next();
            while ($uknown->valid()) {
                $nNode->nameContentAtt[] = $uknown->current();
                $uknown->next();
            }
            return $nNode;
        }
        return new Node($uknown,$content,$att);
    }
    
    public function __invoke($uknown, $content=null, $att=null) {
        if ($uknown instanceof \Generator)
            foreach ($uknown as $yVal)
                $this->nameContentAtt[] = $yVal;
        else
            $this->nameContentAtt[] = [$uknown,$content,$att];
        return $this;
    }
    
    static function asChild($func, $name,string $att='', $between=null) {
        if (is_array($name)) 
        foreach ($name as $i => $tag) 
            echo "<" . (is_array($tag) ? self::wots($tag[0]) . " ". ($tag[1] ?? '') : self::wots($tag)) .">\n";
        else       
            echo "<".self::wots($name).' '.$att.">\n";
        if ($between) 
            self::nodes($between[0],$between[1],$between[2]);
        CallDelegations::delegate($func);
        if (isset($i))
            do {
                $tag = is_array($name[$i]) ? $name[$i][0] : $name[$i];
                if (substr($tag,-1) !== '/')
                    echo "</$tag>\n";
            } while(--$i >=0);
        else
            echo "</$name>\n";
    }
    
    private static function wots($tag) {
        return substr($tag,-1) === '/' ? substr($tag,0,-1) : $tag;
    }

}