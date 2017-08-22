<?php

namespace Document;
class Doc extends DocWriter {
    
    /*
    \| Cleans HTML code
    \| @var array $site Stores html, head, and body
    */
    protected $site = [null, null, null];
    
    /*
    \| Doc constructor
    */
    function __construct(){
        parent::createDoc($this->site[0],$this->site[1], $this->site[2]);
    }
    
    /*
    \| Cleans HTML code
    \| @param string $html | HTML string
    \| @return string
    */
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

    /*
    \| Render/Output HTML
    \| @output bool True to output HTML, false to return string
    \| @return string
    */
    public function render($output = false){
        $html = $this->clean_html($this->html()->toHTML());
        if(!$output) return $html;
        echo $html;
    }
    
    /*
    \| HTML Element
    \| @return DocElement
    */
    public function html(){
        return $this->site[0];
    }
    
    /*
    \| HEAD Element
    \| @return DocElement
    */
    public function head(){
        return $this->site[1];
    }
    
    /*
    \| BODY Element
    \| @return DocElement
    */
    public function body() {
        return $this->site[2];
    }
    
}

class DocWriter
{
    
    /*
    \| Create tag
    \| @param string $tagname The tagname of your element
    \| @param array $attributes The attributes of your element
    \| @param string $innerHTML The innerHTML content of your element
    \| @return DocElement
    */
    static function createTag($tagname, $attributes = array(), $innerHTML = null)
    {
        return new DocElement($tagname, $attributes, $innerHTML);
    }
    
    /*
    \| Create document
    \| @param string $html Variable Reference
    \| @param string $head Variable Reference
    \| @param string $body Variable Reference
    \| @return $html, $head, $hody
    */
    static function createDoc(&$html, &$head, &$body)
    {
        $head = new DocElement("head");
        $body = new DocElement("body", []);
        $html = (new DocElement("html", ["lang"=>"en"]))->addChild($head, $body);
    }
}

class DocElement
{
    
    /*
    \| $var string | Element Tag Name
    */
    private $tagname;
    
    /*
    \| $var array | Element attributes
    */
    private $attributes;
    
    /*
    \| $var string | Element inner HTML
    */
    private $innerHTML;
    
    /*
    \| $var array | Child elements of this element
    */
    private $elements = array();
    
    /*
    \| DocElement Contructor
    \| @param string $a | Element Tag Name
    \| @param array $b | Element Attributes
    \| @param string $c | Element InnerHTML
    \| @return DocElement
    */
    function __construct($a, $b = array(), $c = null)
    {
        $this->tagname    = $a;
        $this->attributes = $b;
        $this->innerHTML  = $c;
    }
    
    /*
    \| Create child tag in this element
    \| @param string $tagname | Element Tag Name
    \| @param array $attributes | Element Attributes
    \| @param string $innerHTML | Element Inner HTML
    \| @param string $element | Variable Reference (Returns created tag)
    \| @return DocElement
    */
    public function createTag($tagname, $attributes = array(), $innerHTML = null, &$element = null) {
        $element = new DocElement($tagname, $attributes, $innerHTML);
        $this->addChild($element);
        return $element;
    }
    
    function __destruct(){
        unset($this->tagname, $this->attributes, $this->innerHTML, $this->elements);
    }
    
    /*
    \| Convert Element to HTML
    \| @return string
    */
    function __toString()
    {
        return $this->toHTML();
    }
    
    /*
    \| Append HTML content
    \| @param string $a | InnerHTML content
    \| @return void
    */
    public function appendHTML($a){
        $this->innerHTML .= $a;
    }
    
    /*
    \| Convert Element to HTML
    \| @param bool $output | True will output the html, false will return the html
    \| @return string
    */
    function toHTML($output = false)
    {
        $attributes = $this->renderAttributes();
        $html       = $this->innerHTML;
        $html .= $this->renderElements();
        $tagname = $this->tagname;
        $render  = "<{$tagname}{$attributes}>{$html}</{$tagname}>";
        if(!$output) return $render;
        echo $render;
    }
    
    /*
    \| Add child (DocElement) to this Element
    \| @param DocElement $a 
    \| @return DocElement | Returns $this
    */
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
    
    /*
    \| Set Element Attribute
    \| @param string $name | Name of your attribute
    \| @param string $value | Your attribute value
    \| @return DocElement | Returns $this
    */
    function attr($name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }
    
    /*
    \| Renders Element Child Elements
    */
    private function renderElements()
    {
        $a = "";
        foreach ($this->elements as $element) {
            $a .= $element->toHTML();
        }
        return $a;
    }
    
    /*
    \| Renders Element Attributes
    */
    private function renderAttributes()
    {
        $a = "";
        foreach ($this->attributes as $n => $v) {
            $a .= " $n=\"$v\"";
        }
        return $a;
    }
    
}
