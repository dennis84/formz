<?php

namespace Rucula;

use Rucula\Type\TypeInterface;

class Rucula extends \Pimple
{
    use Mapping, Optional, Multiple;

    public function __construct()
    {
        $this['type.form']           = new Type\FormType();
        $this['type.text']           = new Type\TextType();
        $this['type.non_empty_text'] = new Type\NonEmptyTextType();
        $this['type.boolean']        = new Type\BooleanType();
        //$this['data.mapper']         = new Util\DataMapper();
        //$this['builder.tuple']       = new Builder\Tuple($this['data.mapper']);
        $this->dataMapper = new Util\DataMapper();
    }
}
