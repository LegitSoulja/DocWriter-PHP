# DocWriter-PHP
Something weird, maybe useful. Who knows.


#### \DocWriter\DocWriter as DocWriter
> DocWriter only does one thing. Returns a DocElement object, in which is your element, and element properties. This is how you create your element.

```php
use Document;

// Create new Document
$document = new Doc();

// Add title to head
$document->head()->createTag("title", [], "DocWriter-PHP");

// Add a paragraph to body
$document->body()->createTag("p", [], "Welcome!. DocWriter just generated this page with ease.");

// Render document
$document->render(true); // true will output the html, however false will return the html string
```


#### \DocWrtier\DocElement as DocElement
```php
// This was the old way to create a document.
$html = DocWriter::createDoc("html");
$head = DocWriter::createDoc("head");
$body = DocWriter::createDoc("body");

// Append head, and body and html
$html->addChild($head, $body);

// Add title to head
$head->createTag("title", [], 'DocWriter');

// Create new div in body
$body->createTag("div", ["class"=>"container"], null, $div); // the 4th argument of createTag of an element can accept a variable, in will will be used with the created tag, without an extra line of code creating div

// Add Header2 to div
$div->createTag("h2", ['class'=>'hey_text', 'style'=>'color:red'], 'Hey');

// Render document (html). Html is the parent of your document, in which will render everything.
$html->toHTML(true);

/*
Output:

<html lang="en">
  <head>
    <title>DocWriter</title>
  </head>
  <body>
    <div class="container">
      <h2 class="hey_text" style="color:red">Hey</h2>
    </div>
  </body>
</html>

*/

```
