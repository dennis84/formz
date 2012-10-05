<?php

namespace Rucola;

use Rucola\Type\TypeInterface;

trait Type
{
    protected $types = array();
    private $typesLoaded = false;

    public function type($name)
    {
        if (false === $this->typesLoaded) {
            $this->loadDefaultTypes();
        }

        if (!array_key_exists($name, $this->types)) {
            throw new \InvalidArgumentException(sprintf('The type with name "%s" does not exists.', $name));
        }

        return $this->types[$name];
    }

    public function registerType(TypeInterface $type)
    {
        $this->types[$type->getName()] = $type;
    }

    private function loadDefaultTypes()
    {
        $this->typesLoaded = true;

        $defaults = array(
            new Type\FormType(),
            new Type\TextType(),
            new Type\NonEmptyTextType(),
            new Type\BooleanType(),
        );

        foreach ($defaults as $type) {
            $this->registerType($type);
        }
    }
}
