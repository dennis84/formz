<?php

namespace Rucula;

trait Optional
{
    public function optional(Field $field)
    {
        $field->setOptional(true);
        return $field;
    }
}
