<?php
namespace Formz\Tests;

use Formz\Builder;
use Formz\Field;

class FieldTest extends FormzTestCase
{
    public function testGetChild()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
            $builder->field('address', [
                $builder->field('street'),
            ]),
        ]);

        $this->assertInstanceOf('Formz\Field', $form->getChild('username'));
        $this->assertSame('username', $form->getChild('username')->getInternalName());
    }

    public function testGetChildFail()
    {
        $this->setExpectedException('InvalidArgumentException');

        $builder = new Builder();
        $form = $builder->form([
            $builder->field('username'),
        ]);

        $form->getChild('password');
    }

    public function testOffsetExists()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('username'),
        ]);

        $this->assertTrue(isset($form['username']));
        $this->assertFalse(isset($form['password']));
    }

    public function testOffsetSet()
    {
        $this->setExpectedException('BadMethodCallException');

        $field = $this->createField('foo');
        $field['foo'] = 'bar';
    }

    public function testOffsetUnset()
    {
        $this->setExpectedException('BadMethodCallException');

        $field = $this->createField('foo');
        unset($field['foo']);
    }

    public function testValidExtensionMethod()
    {
        $field = $this->createField('foo', [
            new \Formz\Tests\Fixtures\FooExtension() ]);

        $return = $field->foo();
        $this->assertEquals($return, $field);
    }

    public function testUndefinedExtensionMethod()
    {
        $this->setExpectedException('BadMethodCallException');

        $field = $this->createField('foo');
        $field->foo();
    }

    public function testIteratorAggregate()
    {
        $foo = $this->createField('foo');
        $bar = $this->createField('bar');
        $baz = $this->createField('baz');

        $foo->setChildren([ $bar, $baz ]);

        foreach ($foo as $child) {
            $this->assertInstanceOf('Formz\Field', $child);
        }
    }

    public function testCount()
    {
        $foo = $this->createField('foo');
        $bar = $this->createField('bar');
        $baz = $this->createField('baz');

        $foo->setChildren([ $bar, $baz ]);
        $this->assertCount(2, $foo);
    }

    public function testGetTransformers()
    {
        $a = new \Formz\Transformer\Integer;
        $b = new \Formz\Transformer\Float;
        $c = new \Formz\Transformer\Boolean;

        $foo = $this->createField('foo');
        $foo->transform($a);
        $foo->transform($b);
        $foo->transform($c);

        $transformers = $foo->getTransformers();
        $this->assertEquals($a, $transformers[0]);
        $this->assertEquals($b, $transformers[1]);
        $this->assertEquals($c, $transformers[2]);

        $foo = $this->createField('foo');
        $foo->transform($a, 0);
        $foo->transform($b, 2);
        $foo->transform($c, 1);

        $transformers = $foo->getTransformers();
        $this->assertEquals($b, $transformers[0]);
        $this->assertEquals($c, $transformers[1]);
        $this->assertEquals($a, $transformers[2]);
    }

    public function testSetAndGetOption()
    {
        $foo = $this->createField('foo');
        $foo->setOption('foo', 'Foo');
        $this->assertSame('Foo', $foo->getOption('foo'));
    }

    public function testGetOptionFail()
    {
        $this->setExpectedException('InvalidArgumentException');
        $foo = $this->createField('foo');
        $foo->getOption('foo');
    }
}
