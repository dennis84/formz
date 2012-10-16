<?php

namespace Rucola\Util;

use Rucola\Field;

class DataMapper
{
    public function fieldToArray(Field $tree)
    {
        $data = array();
        foreach ($tree->getChildren() as $child) {
            $data[$child->getName()] = $child->getValue();
        }

        return $data;
    }

    public function dataToObject(array $data, $obj)
    {
        $r = new \ReflectionObject($obj);
        foreach ($r->getProperties() as $prop) {
            if (isset($data[$prop->getName()])) {
                $prop->setAccessible(true);
                $prop->setValue($obj, $data[$prop->getName()]);
            }
        }

        return $obj;
    }

    public function objectToArray($obj)
    {
        $attributs = array();
        $r = new \ReflectionObject($obj);
        
        foreach ($r->getProperties() as $prop) {
            $prop->setAccessible(true);
            $attributs[$prop->getName()] = $prop->getValue($obj);
        }

        return $attributs;
    }
}
