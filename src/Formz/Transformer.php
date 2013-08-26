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
     * Transforms the incoming data.
     *
     * @param mixed $data The incoming data
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
