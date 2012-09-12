<?php

namespace Rucula;

use Rucula\Type\TypeInterface;

class Rucula extends \Pimple
{
    public function __construct()
    {
        $this['type.form']           = new Type\FormType();
        $this['type.text']           = new Type\TextType();
        $this['type.non_empty_text'] = new Type\NonEmptyTextType();
        $this['data.mapper']         = new Util\DataMapper();
        $this['builder.tuple']       = new Builder\Tuple($this['data.mapper']);
    }

    public function optional(Field $field)
    {
        $field->setOptional(true);
        return $field;
    }
}
