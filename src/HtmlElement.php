<?php

namespace Vdhicts\Html;

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
    private $tag = '';

    /**
     * All the attributes and their values.
     * @var array
     */
    private $attributes = [];

    /**
     * The inner text of the HTML element.
     * @var string
     */
    private $text = '';

    /**
     * HtmlElement constructor.
     * @param string $tag the name of the HTML tag
     * @param string $text
     * @param array $attributes
     */
    public function __construct(string $tag = 'p', string $text = '', array $attributes = [])
    {
        $this->setTag($tag);
        $this->setText($text);
        $this->setAttributes($attributes);
    }

    /**
     * Returns the name of the HTML tag.
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * Sets the name of the HTML tag.
     * @param $tag
     * @return $this
     */
    public function setTag(string $tag): self
    {
        // Store the HTML tag as lowercase
        $this->tag = strtolower($tag);

        return $this;
    }

    /**
     * Returns an array of all attributes set.
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Returns the value of the attribute. If the value isn't set, it returns the fallback;
     * @param string $attribute
     * @return array
     */
    public function getAttribute(string $attribute): array
    {
        // Determine if the attribute is present
        if (! array_key_exists($attribute, $this->attributes)) {
            return [];
        }

        // Return the value of the attribute
        return $this->attributes[$attribute];
    }

    /**
     * Set the value of multiple attributes at once.
     * @param array $attributesValues
     * @return $this
     */
    public function setAttributes(array $attributesValues): self
    {
        foreach ($attributesValues as $attribute => $value) {
            $this->setAttribute($attribute, $value);
        }

        return $this;
    }

    /**
     * Sets the value of an attribute. If the attribute is already set, it overwrites the current value.
     * @param string $attribute
     * @param array|string $value
     * @return $this
     */
    public function setAttribute(string $attribute, $value = []): self
    {
        $this->attributes[$attribute] = $this->wrap($value);

        return $this;
    }

    /**
     * Adds a value to the attribute.
     * @param string $attribute
     * @param string $value
     * @return $this
     */
    public function addAttributeValue(string $attribute, string $value = ''): self
    {
        // Retrieve the current values of the attribute
        $values = $this->getAttribute($attribute);

        // Merge the values
        if (! in_array($value, $values)) {
            $values[] = $value;
        }

        // Store the new values
        $this->setAttribute($attribute, $values);

        return $this;
    }

    /**
     * Remove all attributes at once.
     * @return $this
     */
    public function removeAttributes(): self
    {
        $this->attributes = [];

        return $this;
    }

    /**
     * Removes an attribute.
     * @param string $attribute
     * @return $this
     */
    public function removeAttribute(string $attribute): self
    {
        // Determine if the attribute is present, unset if present
        if (array_key_exists($attribute, $this->attributes)) {
            unset($this->attributes[$attribute]);
        }

        return $this;
    }

    /**
     * Removes a value from the attribute.
     * @param string $attribute
     * @param string $value
     * @return $this
     */
    public function removeAttributeValue(string $attribute, string $value): self
    {
        // Only remove value if attribute is present
        if (! array_key_exists($attribute, $this->attributes)) {
            return $this;
        }

        // Remove the value from the attribute
        $values = array_filter(
            $this->getAttribute($attribute),
            function ($attributeValue) use ($value) {
                return $attributeValue !== $value;
            }
        );

        // Stores the new values
        $this->setAttribute($attribute, $values);

        return $this;
    }

    /**
     * Wraps the value in an array.
     * @param array|string $value
     * @return array
     */
    private function wrap($value): array
    {
        if (! is_array($value)) {
            return [$value];
        }

        return $value;
    }

    /**
     * Returns the innertext of the HTML element.
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Adds text to the innertext of the HTML element.
     * @param string $text
     * @return $this
     */
    public function addText(string $text = ''): self
    {
        $this->text .= $text;

        return $this;
    }

    /**
     * Sets the innertext of the HTML element.
     * @param string $text
     * @return $this
     */
    public function setText(string $text = ''): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Inject the current HTML element with another HTML element.
     * @param HtmlElement $htmlElement
     * @return $this
     */
    public function inject(HtmlElement $htmlElement): self
    {
        $this->addText($htmlElement->generate());

        return $this;
    }

    /**
     * Generates the HTML for the attribute.
     * @param string $attribute
     * @param array $values
     * @return string
     */
    private function generateAttribute(string $attribute, array $values = []): string
    {
        if (! is_numeric($attribute)) {
            return sprintf(
                '%s="%s"',
                $attribute,
                htmlentities(trim(implode(' ', $values)))
            );
        }

        return implode(' ', $values);
    }

    /**
     * Generates the HTML for the attributes of the HTML element.
     * @return string
     */
    private function generateTagAttributes(): string
    {
        // When no attributes present, return empty string
        if (count($this->getAttributes()) === 0) {
            return '';
        }

        // Collect the attributes
        $renderedAttributes = [];
        foreach ($this->getAttributes() as $attribute => $value) {
            // Attributes can have a value or not, it just present (i.e. checked)
            $renderedAttributes[] = $this->generateAttribute($attribute, $value);
        }

        // Join the attributes with a space
        return implode(' ', $renderedAttributes);
    }

    /**
     * Generates the HTML tag with it's attributes and text.
     * @return string
     */
    public function generate(): string
    {
        // The tag name is required
        if ($this->getTag() === '') {
            return '';
        }

        // Generate the attribute string
        $tagAttributes = $this->generateTagAttributes();

        // When attributes are provided, add a space between the tag and the attributes
        $tagSeparator = '';
        if ($tagAttributes !== '') {
            $tagSeparator = ' ';
        }

        // Create the opening tag
        $openingTag = sprintf(
            '<%s%s%s>',
            $this->getTag(),
            $tagSeparator,
            $tagAttributes
        );

        // Create the closing tag
        $closingTag = '';
        if (! in_array($this->getTag(), self::SELF_CLOSING_TAGS)) {
            $closingTag = sprintf('</%s>', $this->getTag());
        }

        // Collect the tag information
        return $openingTag . $this->getText() . $closingTag;
    }

    /**
     * Generate the HTML for the HTML element and output it directly.
     */
    public function output()
    {
        echo $this->generate();
    }
}
