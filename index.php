<?php
include 'DocWriter\DocWriter.php';

use Document;

// Method #1 (Using Doc)


// create new Document (Doc)
$DOM = new Doc();

// add title to head
$DOM->head()->addChild("title", [], "DocWriter");
// You can also add whatever html needed to the inner html.
$div = DocWriter::createTag("div", ["class"=>"container"], "<b>Div</b>");
// add div to body
$DOM->body()->addChild($div);
// Simple way of adding tags without hogging memory
$DOM->body()->createTag("div");
// render page
$DOM->render(true);



// Method #2 (Using DocWriter)


// Create new document
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
