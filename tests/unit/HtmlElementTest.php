<?php
use PHPUnit\Framework\TestCase;
use Vdhicts\Dicms\Html;

/**
 * Class HtmlElementTest
 */
class HtmlElementTest extends TestCase
{
    public function testExistence()
    {
        $this->assertTrue(class_exists(Html\Element::class));
    }

    public function testWithoutTag()
    {
        $htmlElement = new Html\Element('');
        $this->assertSame('', $htmlElement->generate());
    }

    public function testTag()
    {
        $htmlElement = new Html\Element('p');
        $this->assertSame('<p></p>', $htmlElement->generate());
    }

    public function testTagWithText()
    {
        $htmlElement = new Html\Element('p', 'test');
        $this->assertSame('<p>test</p>', $htmlElement->generate());

        $htmlElement = new Html\Element('p');
        $htmlElement->setText('test');
        $this->assertSame('<p>test</p>', $htmlElement->generate());
    }

    public function testTagWithAddingText()
    {
        $htmlElement = new Html\Element('p', 'test');
        $this->assertSame('<p>test</p>', $htmlElement->generate());

        $htmlElement->addText('ie');
        $this->assertSame('<p>testie</p>', $htmlElement->generate());
    }

    public function testTagWithTextRetrieval()
    {
        $htmlElement = new Html\Element('p', 'test');
        $this->assertSame('test', $htmlElement->getText());
    }

    public function testTagWithTextWithAttribute()
    {
        $htmlElement = new Html\Element('p', 'test', ['class' => 'center']);
        $this->assertSame('<p class="center">test</p>', $htmlElement->generate());

        $htmlElement = new Html\Element('p');
        $htmlElement->setText('test');
        $htmlElement->setAttribute('class', 'center');
        $this->assertSame('<p class="center">test</p>', $htmlElement->generate());
    }

    public function testTagWithTextWithAttributes()
    {
        $htmlElement = new Html\Element('p', 'test', ['class' => 'center']);
        $this->assertSame('<p class="center">test</p>', $htmlElement->generate());

        $htmlElement = new Html\Element('p');
        $htmlElement->setText('test');
        $htmlElement->setAttributes(['class' => 'center', 'data-type' => 'paragraph']);
        $this->assertSame('<p class="center" data-type="paragraph">test</p>', $htmlElement->generate());
    }

    public function testTagWithTextWithAttributeWithoutValue()
    {
        $htmlElement = new Html\Element('option', 'test', ['selected']);
        $this->assertSame('<option selected>test</option>', $htmlElement->generate());

        $htmlElement = new Html\Element('option', 'test', ['value' => 1, 'selected']);
        $this->assertSame('<option value="1" selected>test</option>', $htmlElement->generate());
    }

    public function testTagWithAddingAttributeValues()
    {
        $htmlElement = new Html\Element('p', 'test', ['class' => 'center']);
        $this->assertSame('<p class="center">test</p>', $htmlElement->generate());

        $htmlElement->addAttributeValue('class', 'text-success');
        $this->assertSame('<p class="center text-success">test</p>', $htmlElement->generate());
    }

    public function testTagWithRemovingAttribute()
    {
        $htmlElement = new Html\Element('p', 'test', ['class' => 'center']);
        $this->assertSame('<p class="center">test</p>', $htmlElement->generate());

        $htmlElement->removeAttribute('class');
        $this->assertSame('<p>test</p>', $htmlElement->generate());
    }

    public function testTagWithRemovingAttributes()
    {
        $htmlElement = new Html\Element('p', 'test', ['class' => 'center', 'data-type' => 'paragraph']);
        $this->assertSame('<p class="center" data-type="paragraph">test</p>', $htmlElement->generate());

        $htmlElement->removeAttributes();
        $this->assertSame('<p>test</p>', $htmlElement->generate());
    }

    public function testTagWithRemovingAttributeValue()
    {
        $htmlElement = new Html\Element('p', 'test', ['class' => 'center']);
        $this->assertSame('<p class="center">test</p>', $htmlElement->generate());

        $htmlElement->addAttributeValue('class', 'text-success');
        $this->assertSame('<p class="center text-success">test</p>', $htmlElement->generate());

        $htmlElement->removeAttributeValue('class', 'center');
        $this->assertSame('<p class="text-success">test</p>', $htmlElement->generate());

        $htmlElement->removeAttributeValue('role', 'alert');
        $this->assertSame('<p class="text-success">test</p>', $htmlElement->generate());
    }

    public function testTagWithAttributesRetrieval()
    {
        $htmlElement = new Html\Element('p', 'test', ['class' => 'center']);
        $this->assertSame(['center'], $htmlElement->getAttribute('class'));
        $this->assertSame(['class' => ['center']], $htmlElement->getAttributes());

        $htmlElement = new Html\Element('p', 'test');
        $this->assertSame([], $htmlElement->getAttribute('class'));
    }

    public function testTagInjection()
    {
        $optionElement = new Html\Element('option', 'option 1', ['value' => 1]);
        $this->assertSame('<option value="1">option 1</option>', $optionElement->generate());

        $selectElement = new Html\Element('select', '', ['name' => 'something']);
        $this->assertSame('<select name="something"></select>', $selectElement->generate());

        $selectElement->inject($optionElement);
        $this->assertSame('<select name="something"><option value="1">option 1</option></select>',
            $selectElement->generate());
    }

    public function testDirectOutput()
    {
        $htmlElement = new Html\Element('p', 'test', ['class' => 'center']);

        $this->expectOutputString('<p class="center">test</p>');
        $htmlElement->output();
    }
}
