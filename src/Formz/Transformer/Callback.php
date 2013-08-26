<?php

namespace Formz\Transformer;

use Formz\Transformer;

/**
 * Callback.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Callback extends Transformer
{
    protected $transform;
    protected $reverseTransform;

    /**
     * Constructor.
     *
     * @param Closure $transform        The tranform callback
     * @param Closure $reverseTransform The reverse tranform callback
     */
    public function __construct(\Closure $transform = null, \Closure $reverseTransform = null)
    {
        $this->transform = $transform;
        $this->reverseTransform = $reverseTransform;
    }

    /**
     * {@inheritDoc}
     */
    public function transform($data)
    {
        return $this->doTransform($data, $this->transform);
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform($data)
    {
        return $this->doTransform($data, $this->reverseTransform);
    }

    /**
     * Maybe executes the given transform function.
     *
     * @param mixed   $data The data to tranform
     * @param Closure $func The callback function
     *
     * @return mixed
     */
    private function doTransform($data, \Closure $func = null)
    {
        if (null === $func || null === $data) {
            return $data;
        }

        return call_user_func_array($func, $data);
    }
}
