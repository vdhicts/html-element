<?php

namespace Vdhicts\Html;

/**
 * Class HtmlElement
 * @package App\Libraries
 */
class HtmlElement
{
    /**
     * Array of self closing tags (tags who don't need the </ variant).
     */
    const SELF_CLOSING_TAGS = [
        'input',
        'img',
        'hr',
        'br',
        'meta',
        'link'
    ];

    /**
     * The name of the HTML tag.
     * @var string
     */
    private $tagName = '';

    /**
     * All the attributes and their values.
     * @var array
     */
    private $attributes = [];

    /**
     * The innertext of the HTML element.
     * @var string
     */
    private $text = '';

    /**
     * HtmlElement constructor.
     * @param string $tagName the name of the HTML tag
     * @param string $text
     * @param array $attributes
     */
    public function __construct($tagName = 'p', $text = '', $attributes = [])
    {
        $this->setTagName($tagName);
        $this->setText($text);
        $this->setAttributes($attributes);
    }

    /**
     * Returns the name of the HTML tag.
     * @return string
     */
    private function getTagName()
    {
        return $this->tagName;
    }

    /**
     * Sets the name of the HTML tag.
     * @param $tagName
     * @return $this
     */
    private function setTagName($tagName)
    {
        // Store the HTML tag as lowercase
        $this->tagName = strtolower($tagName);

        return $this;
    }

    /**
     * Returns the value of the attribute. If the value isn't set, it returns the fallback;
     * @param string $attribute
     * @param string $fallback
     * @return string
     */
    public function getAttribute($attribute, $fallback = '')
    {
        // Determine if the attribute is present
        if (!isset($this->attributes[$attribute])) {
            return $fallback;
        }

        // Return the value of the attribute
        return $this->attributes[$attribute];
    }

    /**
     * Adds a value to the attribute.
     * @param string $attribute
     * @param string $value
     * @return $this
     */
    public function addAttribute($attribute, $value = '')
    {
        // A single value might be provided
        if (!is_array($value)) {
            $value = [$value];
        }

        // Retrieve the current values of the attribute
        $values = explode(' ', $this->getAttribute($attribute));

        // Merge the values
        $values = array_merge($values, $value);

        // Store the new values
        $this->setAttribute($attribute, implode(' ', $values));

        return $this;
    }

    /**
     * Sets the value of an attribute. If the attribute is already set, it overwrites the current value.
     * @param string $attribute
     * @param string $value
     * @return $this
     */
    public function setAttribute($attribute, $value = '')
    {
        $this->attributes[$attribute] = $value;

        return $this;
    }

    /**
     * Returns the innertext of the HTML element.
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Adds text to the innertext of the HTML element.
     * @param string $text
     * @return $this
     */
    public function addText($text = '')
    {
        $this->text .= $text;

        return $this;
    }

    /**
     * Sets the innertext of the HTML element.
     * @param string $text
     * @return $this
     */
    public function setText($text = '')
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Removes an attribute.
     * @param string $attribute
     * @return $this
     */
    public function removeAttribute($attribute)
    {
        // Determine if the attribute is present, unset if present
        if (isset($this->attributes[$attribute])) {
            unset($this->attributes[$attribute]);
        }

        return $this;
    }

    /**
     * Returns an array of all attributes set.
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set the value of multiple attributes at once.
     * @param array $attributesValues
     * @return $this
     */
    public function setAttributes(array $attributesValues)
    {
        $this->attributes = array_merge($this->getAttributes(), $attributesValues);

        return $this;
    }

    /**
     * Remove all attributes at once.
     * @return $this
     */
    public function removeAttributes()
    {
        $this->attributes = [];

        return $this;
    }

    /**
     * Inject the current HTML element with another HTML element.
     * @param HtmlElement $htmlElement
     * @return $this
     */
    public function inject(HtmlElement $htmlElement)
    {
        $this->addText($htmlElement->generate());

        return $this;
    }

    /**
     * Generates the HTML for the attributes of the HTML element.
     * @return string
     */
    private function generateTagAttributes()
    {
        // When no attributes present, return empty string
        if (count($this->getAttributes()) === 0) {
            return '';
        }

        // Collect the attributes
        $attributes = [];
        foreach ($this->getAttributes() as $attribute => $value) {
            // Attributes can have a value
            if (!is_numeric($attribute)) {
                $attributes[] = sprintf('%s="%s"', $attribute, htmlentities(trim($value)));
                continue;
            }

            // Attributes hasn't got a value, it just present (i.e. checked)
            $attributes[] = $value;
        }

        // Join the attributes with a space
        return ' ' . implode(' ', $attributes);
    }

    /**
     * Generates the HTML tag with it's attributes and text.
     * @return string
     */
    public function generate()
    {
        // The tag name is required
        if ($this->getTagName() === '') {
            return '';
        }

        // Collect the tag information
        return sprintf(
            '<%s%s>%s%s',
            $this->getTagName(),
            $this->generateTagAttributes(),
            $this->getText(),
            !in_array($this->getTagName(), self::SELF_CLOSING_TAGS)
                ? sprintf('</%s>', $this->getTagName())
                : ''
        );
    }

    /**
     * Generate the HTML for the HTML element and output it directly.
     */
    public function output()
    {
        echo $this->generate();
    }
}
