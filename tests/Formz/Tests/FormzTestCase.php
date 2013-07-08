<?php

namespace Formz\Tests;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Formz\Field;

class FormzTestCase extends \PHPUnit_Framework_TestCase
{
    protected function createField($name, array $extensions = [])
    {
        return new Field($name, new EventDispatcher(), $extensions);
    }
}
