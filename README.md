# DocWriter-PHP
Something weird, maybe useful. Who knows.


#### \DocWriter\DocWriter as DocWriter
> DocWriter only does one thing. Returns a DocElement object, in which is your element, and element properties. This is how you create your element.

```php
/*
* @param tagName | The name of the creating tag
* @param attributes | The tag attributes
* @innerHTML | The tag inner HTML content
*/
$tagName = "article";
$attributes = array("style"=>"color:orange");
$innerHTML = "YAY, I'm an orange colored element.";
DocWriter::createTag($tagName, $attributes, $innerHTML) // returns DocElement object

```


#### \DocWrtier\DocElement as DocElement
```php
DocWriter::createDoc($html, $head, $body);
$head->addChild(DocWriter::createTag("title", [], 'DocWriter'));
$div = DocWriter::createTag('div', ['class'=>'container']);
$div->addChild(DocWriter::createTag("h2", ['class'=>'hey_text', 'style'=>'color:red'], 'Hey'));
$body->addChild($div);
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
