<?php

namespace Rucola\Util;

use Rucola\Field;

/**
 * DataMapper.
 */
class DataMapper
{
    /**
     * Maps the field object to an array. This is the default field apply
     * strategy if no custom apply method is set.
     *
     * @param Field $field The field object
     *
     * @return array
     */
    public static function fieldToArray(Field $tree)
    {
        $data = array();
        foreach ($tree->getChildren() as $child) {
            $data[$child->getName()] = $child->getValue();
        }

        return $data;
    }

    /**
     * Maps the an array of data to an object.
     *
     * @param array  $data   The data array
     * @param object $object The object
     *
     * @return object
     */
    public static function dataToObject(array $data, $obj)
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
}
