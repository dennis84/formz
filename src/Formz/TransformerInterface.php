<?php

namespace Formz;

/**
 * TransformerInterface.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
interface TransformerInterface
{
    /**
     * Transforms the incoming data.
     *
     * @param mixed $data The incoming data
     */
    function transform($data);
}
