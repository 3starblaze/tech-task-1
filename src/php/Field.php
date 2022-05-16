<?php

namespace TechTask\Field;

/**
 * PHP representation of an input element.
 *
 * This class is used to define input attributes and to convert the incoming
 * data to a PHP-friendly value.
 */
class Field implements \JsonSerializable
{
    private $label;

    private $name;

    private $styleId;

    private $converter;

    private $attributes;

    /**
     * Constructor
     *
     * @param $label The content of `<label>` tag
     * @param $name The `<input>` tag's name attribute
     * @param $styleId CSS id for the input tag
     * @param Callable that converts string into specific product's
     * constructor argument
     * @param $attributes Extra html attributes that are added to the input
     */
    public function __construct(
        string $label,
        string $name,
        string $styleId,
        callable $converter,
        array $attributes = []
    ) {
        $this->label = $label;
        $this->name = $name;
        $this->styleId = $styleId;
        $this->converter = $converter;
        $this->attributes = $attributes;
    }

    public function jsonSerialize()
    {
        return [
            'label' => $this->label,
            'name' => $this->name,
            'styleId' => $this->styleId,
            'attributes' => $this->attributes,
        ];
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getStyleId()
    {
        return $this->styleId;
    }

    public function getConverter()
    {
        return $this->converter;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }
}
