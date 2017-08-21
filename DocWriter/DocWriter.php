<?php

namespace Document;
class Doc extends DocWriter {
    
    protected $site = [null, null, null];
    
    function __construct(){
        parent::createDoc($this->site[0],$this->site[1], $this->site[2]);
    }

    private function clean_html($html)
    {
        $dom = new \DOMDocument();

        if (libxml_use_internal_errors(true) === true)
        {
            libxml_clear_errors();
        }

        $html = \mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        $html = preg_replace(array('~\R~u', '~>[[:space:]]++<~m'), array("\n", '><'), $html);

        if ((empty($html) !== true) && ($dom->loadHTML($html) === true))
        {
            $dom->formatOutput = true;

            if (($html = $dom->saveXML($dom->documentElement, LIBXML_NOEMPTYTAG)) !== false)
            {
                $regex = array
                (
                    '~' . preg_quote('<![CDATA[', '~') . '~' => '',
                    '~' . preg_quote(']]>', '~') . '~' => '',
                    '~></(?:area|base(?:font)?|br|col|command|embed|frame|hr|img|input|keygen|link|meta|param|source|track|wbr)>~' => ' />',
                );

                return '<!DOCTYPE html>' . "\n" . preg_replace(array_keys($regex), $regex, $html);
            }
        }

        return false;
    }

    public function render($output = false){
        $html = $this->clean_html($this->html()->toHTML());
        if(!$output) return $html;
        echo $html;
    }
    
    public function html(){
        return $this->site[0];
    }
    
    public function head(){
        return $this->site[1];
    }
    
    public function body() {
        return $this->site[2];
    }
    
}

class DocWriter
{
    static function createTag($tagname, $attributes = array(), $innerHTML = null)
    {
        return new DocElement($tagname, $attributes, $innerHTML);
    }
    static function createDoc(&$html, &$head, &$body)
    {
        $head = new DocElement("head");
        $body = new DocElement("body", []);
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
    
    public function createTag($tagname, $attributes = array(), $innerHTML = null, &$element = null) {
        $element = new DocElement($tagname, $attributes, $innerHTML);
        $this->addChild($element);
        return $element;
    }
    
    function __destruct(){
        unset($this->tagname, $this->attributes, $this->innerHTML, $this->elements);
    }
    
    function __toString()
    {
        return $this->toHTML();
    }
    
    public function appendHTML($a){
        $this->innerHTML .= $a;
    }
    
    function toHTML($output = false, $ignoreWarning = false)
    {
        $attributes = $this->renderAttributes();
        $html       = $this->innerHTML;
        $html .= $this->renderElements();
        $tagname = $this->tagname;
        $render  = "<{$tagname}{$attributes}>{$html}</{$tagname}>";
        if(!$output) return $render;
        echo $render;
    }
    
    function addChild($a)
    {
        $args = func_get_args();
        if (count($args) > 1) {
            foreach ($args as $b) {
                if (!($b instanceof DocElement)) continue;
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
