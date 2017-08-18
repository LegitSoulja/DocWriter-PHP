<?php

namespace Document;

class DocWriter
{
    static function createTag($tagname, $attributes = array(), $innerHTML = null)
    {
        return new DocElement($tagname, $attributes, $innerHTML);
    }
    static function createDoc(&$html, &$head, &$body)
    {
        $head = new DocElement("head");
        $body = new DocElement("body");
        $html = (new DocElement("html", ["lang"=>"en"]))->addChild($head, $body);
    }
}

class DocElement
{
    private $tagname;
    private $attributes;
    private $innerHTML;
    private $elements = array();
    
    function __construct($a, $b = array(), $c = null)
    {
        $this->tagname    = $a;
        $this->attributes = $b;
        $this->innerHTML  = $c;
    }
    
    function __destruct(){
        unset($this->tagname, $this->attributes, $this->innerHTML, $this->elements);
    }
    
    function __toString()
    {
        return $this->toHTML();
    }
    
    function toHTML($output = false)
    {
        $attributes = $this->renderAttributes();
        $html       = $this->innerHTML;
        $html .= $this->renderElements();
        $tagname = $this->tagname;
        $render  = "<{$tagname}{$attributes}>{$html}</{$tagname}>" . PHP_EOL;
        if (in_array("tidy", get_declared_classes())) {
            $tidy = new \tidy();
            $tidy->parseString($render, array(
                "indent" => true
            ), 'utf8');
            $tidy->cleanRepair();
        }
        if(!$output) return (isset($tidy) ? tidy_get_output($tidy) : $render);
        echo (isset($tidy) ? tidy_get_output($tidy) : $render);
    }
    
    function addChild($a)
    {
        if (strtolower(gettype($a) == "array")) {
            foreach ($a as $b) {
                if (!($b instanceof DocElement))
                    continue;
                array_push($this->elements, $b);
            }
            return $this;
        }
        if (!($a instanceof DocElement)) {
            die("Unknown type.");
        }
        array_push($this->elements, $a);
        return $this;
    }
    
    function attr($name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }
    
    private function renderElements()
    {
        $a = "";
        foreach ($this->elements as $element) {
            $a .= $element->toHTML();
        }
        return $a;
    }
    
    private function renderAttributes()
    {
        $a = "";
        foreach ($this->attributes as $n => $v) {
            $a .= " $n=\"$v\"";
        }
        return $a;
    }
    
}
