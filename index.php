<?php
include 'DocWriter\DocWriter.php';

use Document;

// Create your page
DocWriter::createDoc($html, $head, $body);

// Add a title to html
$html->addChild(DocWriter::createTag("title", [], "DocWriter"));

// Create a new iFrame
$iframe = DocWriter::createTag("iframe", array(
    
    // attributes
    "src"=>"http://youtube.com",
    "style"=>"border:0"
    
    // inside html
), "Iframes are not supported for your browser.");

$p = DocWriter::createTag("p", [], "DocWriter - Alternative to writing html");

// add your elements to body
$body->addChild($iframe, $p); // add iframe to body

// render page. Make sure that you render the parent html, instead of a child of html. 
$html->toHTML(true);
