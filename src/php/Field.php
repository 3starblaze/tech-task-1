<?php

namespace TechTask\Field;

class Field implements \JsonSerializable
{
    private $label;

    private $name;

    private $styleId;

    private $attributes;

    /**
     * Constructor
     *
     * @param $label The content of `<label>` tag
     * @param $name The `<input>` tag's name attribute
     * @param $styleId CSS id for the input tag
     * @param $attributes Extra html attributes that are added to the input
     */
    public function __construct(
        string $label,
        string $name,
        string $styleId,
        array $attributes = []
    ) {
        $this->label = $label;
        $this->name = $name;
        $this->styleId = $styleId;
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
}
