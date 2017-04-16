<?php

namespace Document;

class DocWriter
{
    static function createTag($tagname, $attributes = array(), $innerHTML = null)
    {
        return new Document\DocElement($tagname, $attributes, $innerHTML);
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
    function __toString(){
        return $this->toHTML();
    }
    function toHTML()
    {
        $attributes = $this->renderAttributes();
        $html       = $this->innerHTML;
        $html .= $this->renderElements();
        $tagname = $this->tagname;
        $lb      = PHP_EOL;
        return <<<HTML
<{$tagname}{$attributes}>{$html}</{$tagname}>{$lb}
HTML;
    }
    function addChild($a)
    {
        if (strtolower(gettype($a) == "array")) {
            foreach ($a as $b) {
                if (!($b instanceof Document\DocElement))
                    continue;
                array_push($this->elements, $b);
            }
            return;
        }
        if (!($a instanceof Document\DocElement))
            die("Unknown type.");
        array_push($this->elements, $a);
    }
    function attr($name, $value)
    {
        $this->attributes[$name] = $value;
    }
    private function renderElements()
    {
        $a = "";
        foreach ($this->elements as $element)
            $a .= $element->toHTML();
        return $a;
    }
    private function renderAttributes()
    {
        $a = "";
        foreach ($this->attributes as $n => $v)
            $a .= " $n=\"$v\"";
        return $a;
    }
}
