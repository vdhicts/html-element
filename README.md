# Html Element

This package allows you to easily create HTML elements from PHP. It's inspired by 
[David Walsh's blogpost](https://davidwalsh.name/create-html-elements-php-htmlelement-class) but further improved.

```$php

use Vdhicts\Dicms\Html;

// Create new html element with attribute name
$selectElement = new Html\Element('select');
$selectElement->setAttribute('name', 'something');
$selectElement->generate();

// Create new html element with text and attribute
$paragraphElement = new Html\Element('p', 'text', ['class' => 'center']);
```

## Requirements

This package requires PHP 7.

## Installation

This package can be used in any PHP project or with any framework. The packages is tested in PHP 5.6 and PHP 7.0.

You can install the package via composer:

``` bash
composer require vdhicts/html-element
```

## Usage

A new html element can be created with:

```php
$containerElement = new Html\Element('div');
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
$colElement = new Html\Element('div', '', ['class' => 'col-md-6']);
$rowElement = new Html\Element('div', '', ['class' => 'row']);

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
* `inject` to inject a Element into the current Element.
* `generate` generates the Element and returns its string representation.
* `output` echos the result of `generate`.

## Tests

Full code coverage unit tests are available in the `tests` folder. Run via phpunit:

`vendor\bin\phpunit`

By default a coverage report will be generated in the `build/coverage` folder.

## Contribution

Any contribution is welcome, but it should be fully tested, meet the PSR-2 standard and please create one pull request 
per feature. In exchange you will be credited as contributor on this page.

## Security

If you discover any security related issues in this or other packages of Vdhicts, please email info@vdhicts.nl instead
of using the issue tracker.

## License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

## About vdhicts

[Van der Heiden ICT services](https://www.vdhicts.nl) is the name of my personal company for which I work as
freelancer. Van der Heiden ICT services develops and implements IT solutions for businesses and educational
institutions.