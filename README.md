# HtmlElement

This package allows you to easily create HTML elements from PHP. It's inspired by 
[David Walsh's blogpost](https://davidwalsh.name/create-html-elements-php-htmlelement-class) but further improved.

```$php

use Vdhicts\HtmlElement\HtmlElement;

// Create new html element with attribute name
$selectElement = new HtmlElement('select');
$selectElement->setAttribute('name', 'something');
$selectElement->generate();

// Create new html element with text and attribute
$paragraphElement = new HtmlElement('p', 'text', ['class' => 'center']);
```

## Installation

This package can be used in any PHP project or with any framework. The packages is tested in PHP 5.6 and PHP 7.0.

You can install the package via composer:

``` bash
composer require vdhicts/html-element
```

## Usage

A new html element can be created with:

```php
$containerElement = new HtmlElement('div');
$containerElement->setAttribute('class', 'container');
```

The element could contain text:

```php
$containerElement->setText('Hello World');
```

The element could have one or more attributes:

```php
// One attribute
$containerElement->setAttribute('class', 'container');

// Multiple attributes
$containerElement->setAttributes(['class' => 'container', 'role' => 'container']);
```

Or the element could contain another html element:

```php
$colElement = new HtmlElement('div', '', ['class' => 'col-md-6']);
$rowElement = new HtmlElement('div', '', ['class' => 'row']);

$rowElement->inject($colElement);
$containerElement->inject($rowElement);
```

The text and attributes can also be added, which merges with existing text or attributes.

## Methods

* `getTag` or `setTag` to request or change the tag.
* `getAttributes` or `getAttribute(attribute)` to retrieve all attributes with their values or one attributes with its 
value
* `setAttributes` or `setAttribute(attribute, value)` to manipulate all attributes or just one.
* `addAttributeValue` adds a value to an attributes.
* `removeAttributes` or `removeAttribute(attribute)` to remove all attributes or just one.
* `removeAttributeValue(attribute, value)` to remove one value from a attribute.
* `getText`, `addText` or `setText` to retrieve, add or set the inner text.
* `inject` to inject a htmlElement into the current htmlElement.
* `generate` generates the htmlElement and returns its string representation.
* `output` echos the result of `generate`.

## Tests

Full code coverage unit tests are available in the `tests` folder. Run via phpunit:

`vendor\bin\phpunit`

By default a coverage report will be generated in the `build/coverage` folder.

## Contribution

Any contribution is welcome, but it should be fully tested, meet the PSR-2 standard and please create one pull request 
per feature. In exchange you will be credited as contributor on this page.

## License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
