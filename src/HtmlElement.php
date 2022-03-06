<?php

namespace Vdhicts\HtmlElement;

class HtmlElement
{
    /**
     * Array of self-closing tags (tags who don't need the </ variant).
     */
    private const SELF_CLOSING_TAGS = [
        'input',
        'img',
        'hr',
        'br',
        'meta',
        'link'
    ];

    /**
     * The name of the HTML tag.
     */
    private string $tag = '';

    /**
     * All the attributes and their values.
     */
    private array $attributes = [];

    /**
     * The inner text of the HTML element.
     */
    private string $text = '';

    public function __construct(string $tag = 'p', string $text = '', array $attributes = [])
    {
        $this->setTag($tag);
        $this->setText($text);
        $this->setAttributes($attributes);
    }

    /**
     * Returns the name of the HTML tag.
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * Sets the name of the HTML tag.
     */
    public function setTag(string $tag): self
    {
        // Store the HTML tag as lowercase
        $this->tag = strtolower($tag);

        return $this;
    }

    /**
     * Returns an array of all attributes set.
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Returns the value of the attribute. If the value isn't set, it returns the fallback;
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
     * @param array|string $value
     */
    public function setAttribute(string $attribute, $value = []): self
    {
        $this->attributes[$attribute] = $this->wrap($value);

        return $this;
    }

    /**
     * Adds a value to the attribute.
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
     */
    public function removeAttributes(): self
    {
        $this->attributes = [];

        return $this;
    }

    /**
     * Removes an attribute.
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
     */
    private function wrap($value): array
    {
        if (! is_array($value)) {
            return [$value];
        }

        return $value;
    }

    /**
     * Returns the inner text of the HTML element.
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Adds text to the inner text of the HTML element.
     */
    public function addText(string $text = ''): self
    {
        $this->text .= $text;

        return $this;
    }

    /**
     * Sets the inner text of the HTML element.
     */
    public function setText(string $text = ''): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Inject the current HTML element with another HTML element.
     */
    public function inject(HtmlElement $htmlElement): self
    {
        $this->addText($htmlElement->generate());

        return $this;
    }

    /**
     * Generates the HTML for the attribute.
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
     * Generates the HTML tag with its attributes and text.
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
    public function output(): void
    {
        echo $this->generate();
    }
}
