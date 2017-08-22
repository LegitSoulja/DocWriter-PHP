<?php
include 'DocWriter\DocWriter.php';

use Document;

// Method #1 (Using Doc)
// Doc is used to create an entire page html, head, and body without you having to. 
// Doc has 3 methods. html, head, body, and render.


// Create new Doc
$DOM = new Doc();

// Create title element, added to head
$DOM->head()->createTag("title", [], "DocWriter");

// Storing elements (Only when you need to add extra elements to it in the future)
$div = DocWriter::createTag("div", ["class"=>"container"], null);

// create a bold text in div with the content "I am bold"
$div->createTag("b", [], "I am bold");

// Add div to body
$DOM->body()->addChild($div);

// render page (The last thing you should do).
$DOM->render(true);



// Method #2 (Using DocWriter)

// Create new Document
DocWriter::createDoc($html, $head, $body);

/*
This was the old way to do above. Just so you get the idea, understanding.
$html = DocWriter::createTag("html");
$head = DocWriter::createTag("head");
$body = DocWriter::createTag("body");
$html->addChild($head, $body);
*/

// Add a title to head
$head->createTag("title", [], "DocWriter");

// Create a new iFrame
$iframe = DocWriter::createTag("iframe",
    
    // attributes
    [
        "src"=>"http://youtube.com",
        "style"=>"border:0"
    ]
    
    // inside html
, "Iframes are not supported for your browser.");

// Create a p element
$p = DocWriter::createTag("p", [], "DocWriter - Alternative to writing html");

// add your elements to body. They are added in order they are recieved.
$body->addChild($iframe, $p);


// Additions, lets create a test div
$div = DocWriter::createTag("div");

// Now, say for example we want to add a new element to div w/ less code
$div->createTag("table", [], null, $table); // the last argument, which must be a variable will return your created tag
$table->createTag("tr", [], null, $tr);
$tr->createTag("td", [], "Added an row to this table.");

// lets add this div to body
$body->addChild($div);


// render page. Make sure that you render the parent html, instead of a child of html. 
$html->toHTML(true);
