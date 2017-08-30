<?php
use PHPUnit\Framework\TestCase;
use Vdhicts\HtmlElement\HtmlElement;

/**
 * Class HtmlElementTest
 */
class HtmlElementTest extends TestCase
{
    public function testExistence()
    {
        $this->assertTrue(class_exists(HtmlElement::class));
    }

    public function testWithoutTag()
    {
        $htmlElement = new HtmlElement('');
        $this->assertSame('', $htmlElement->generate());
    }

    public function testTag()
    {
        $htmlElement = new HtmlElement('p');
        $this->assertSame('<p></p>', $htmlElement->generate());
    }

    public function testTagWithText()
    {
        $htmlElement = new HtmlElement('p', 'test');
        $this->assertSame('<p>test</p>', $htmlElement->generate());

        $htmlElement = new HtmlElement('p');
        $htmlElement->setText('test');
        $this->assertSame('<p>test</p>', $htmlElement->generate());
    }

    public function testTagWithAddingText()
    {
        $htmlElement = new HtmlElement('p', 'test');
        $this->assertSame('<p>test</p>', $htmlElement->generate());

        $htmlElement->addText('ie');
        $this->assertSame('<p>testie</p>', $htmlElement->generate());
    }

    public function testTagWithTextRetrieval()
    {
        $htmlElement = new HtmlElement('p', 'test');
        $this->assertSame('test', $htmlElement->getText());
    }

    public function testTagWithTextWithAttribute()
    {
        $htmlElement = new HtmlElement('p', 'test', ['class' => 'center']);
        $this->assertSame('<p class="center">test</p>', $htmlElement->generate());

        $htmlElement = new HtmlElement('p');
        $htmlElement->setText('test');
        $htmlElement->setAttribute('class', 'center');
        $this->assertSame('<p class="center">test</p>', $htmlElement->generate());
    }

    public function testTagWithTextWithAttributes()
    {
        $htmlElement = new HtmlElement('p', 'test', ['class' => 'center']);
        $this->assertSame('<p class="center">test</p>', $htmlElement->generate());

        $htmlElement = new HtmlElement('p');
        $htmlElement->setText('test');
        $htmlElement->setAttributes(['class' => 'center', 'data-type' => 'paragraph']);
        $this->assertSame('<p class="center" data-type="paragraph">test</p>', $htmlElement->generate());
    }

    public function testTagWithTextWithAttributeWithoutValue()
    {
        $htmlElement = new HtmlElement('option', 'test', ['selected']);
        $this->assertSame('<option selected>test</option>', $htmlElement->generate());

        $htmlElement = new HtmlElement('option', 'test', ['value' => 1, 'selected']);
        $this->assertSame('<option value="1" selected>test</option>', $htmlElement->generate());
    }

    public function testTagWithAddingAttributeValues()
    {
        $htmlElement = new HtmlElement('p', 'test', ['class' => 'center']);
        $this->assertSame('<p class="center">test</p>', $htmlElement->generate());

        $htmlElement->addAttributeValue('class', 'text-success');
        $this->assertSame('<p class="center text-success">test</p>', $htmlElement->generate());
    }

    public function testTagWithRemovingAttribute()
    {
        $htmlElement = new HtmlElement('p', 'test', ['class' => 'center']);
        $this->assertSame('<p class="center">test</p>', $htmlElement->generate());

        $htmlElement->removeAttribute('class');
        $this->assertSame('<p>test</p>', $htmlElement->generate());
    }

    public function testTagWithRemovingAttributes()
    {
        $htmlElement = new HtmlElement('p', 'test', ['class' => 'center', 'data-type' => 'paragraph']);
        $this->assertSame('<p class="center" data-type="paragraph">test</p>', $htmlElement->generate());

        $htmlElement->removeAttributes();
        $this->assertSame('<p>test</p>', $htmlElement->generate());
    }

    public function testTagWithRemovingAttributeValue()
    {
        $htmlElement = new HtmlElement('p', 'test', ['class' => 'center']);
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
        $htmlElement = new HtmlElement('p', 'test', ['class' => 'center']);
        $this->assertSame(['center'], $htmlElement->getAttribute('class'));
        $this->assertSame(['class' => ['center']], $htmlElement->getAttributes());

        $htmlElement = new HtmlElement('p', 'test');
        $this->assertSame([], $htmlElement->getAttribute('class'));
    }

    public function testTagInjection()
    {
        $optionElement = new HtmlElement('option', 'option 1', ['value' => 1]);
        $this->assertSame('<option value="1">option 1</option>', $optionElement->generate());

        $selectElement = new HtmlElement('select', '', ['name' => 'something']);
        $this->assertSame('<select name="something"></select>', $selectElement->generate());

        $selectElement->inject($optionElement);
        $this->assertSame('<select name="something"><option value="1">option 1</option></select>',
            $selectElement->generate());
    }

    public function testDirectOutput()
    {
        $htmlElement = new HtmlElement('p', 'test', ['class' => 'center']);

        $this->expectOutputString('<p class="center">test</p>');
        $htmlElement->output();
    }
}
