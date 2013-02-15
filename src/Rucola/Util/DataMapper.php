<?php

namespace Rucola\Util;

use Rucola\Field;

/**
 * DataMapper.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
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
        $data = [];
        foreach ($tree->getChildren() as $child) {
            $data[$child->getFieldName()] = $child->getData();
        }

        return $data;
    }
}
