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
$innerHTML = "YAY, I am orange";
\DocWriter\DocWriter::createTag($tagName, (array)$attributes, $innerHTML) // returns DocElement object

```


#### \DocWrtier\DocElement as DocElement
```php
/*
*
*/
$element = \DocWriter\DocWriter::createTag("div", array("class"=>"container"));

// You can add a child DocElement, as innerHTML for a create tag, for example.
$element->addChild(\DocWriter\DocWriter::createTag("h2",array("style"=>"color:red"),"Hey"));

// Dynamically add a attribute
$element->attr("class","hey_text");

// Finally, output results
echo $element->toHTML();

/*

Output:

  <div class="container">
    <h2 class="hey_text" style="color:red">Hey</h2>
  </div>

*/



```
