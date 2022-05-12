<?php

namespace TechTask\Field;

class Field implements \JsonSerializable
{
    private $label;

    private $name;

    private $styleId;

    /**
     * Constructor
     *
     * @param $label The content of `<label>` tag
     * @param $name The `<input>` tag's name attribute
     * @param $styleId CSS id for the input tag
     */
    public function __construct(string $label, string $name, string $styleId)
    {
        $this->label = $label;
        $this->name = $name;
        $this->styleId = $styleId;
    }

    public function jsonSerialize()
    {
        return [
            'label' => $this->label,
            'name' => $this->name,
            'styleId' => $this->styleId,
        ];
    }
}
