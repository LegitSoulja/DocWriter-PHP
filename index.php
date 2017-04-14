<?php
namespace YourSite;
include 'DocWriter\DocWriter.php';

use DocWriter\DocWriter as DocWriter;
use DocWriter\DocElement as DocElement;

// Create your page

$html = DocWriter::createTag("html"); // create html tag
$head = DocWriter::createTag("head"); // create head tag
$body = DocWriter::createTag("body"); // create body tag

$html->addChild(array($head,$body)); // add tag head, and body to html

$iframe = DocWriter::createTag("iframe",array( // create test iframe element.
    "src"=>"http://youtube.com",
    "style"=>"border:0"
),"Iframe's are not supported for your browser.");

$body->addChild($iframe); // add iframe to body

// render page
echo $html->toHTML();
