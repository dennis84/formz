<?php

namespace Formz;

/**
 * Transformer.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
abstract class Transformer
{
    /**
     * Transforms the submitted data.
     *
     * @param mixed $data The submitted data
     */
    public function transform($data)
    {
        return $data;
    }

    /**
     * Transforms the filled data.
     *
     * @param mixed $data The filled data
     */
    public function reverseTransform($data)
    {
        return $data;
    }
}
