<?php 
namespace Formz\Tests;

use Formz\Builder;
use Formz\Field;

class FieldTest extends FormzTestCase
{
    public function testGetName()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
            $builder->field('address', [
                $builder->field('street'),
            ]),
            $builder->field('choices', [
                $builder->field('key'),
                $builder->field('value'),
            ])->multiple(),
        ]);

        $this->assertSame('', $form->getName());
        $this->assertSame('username', $form['username']->getName());
        $this->assertSame('address[street]', $form['address']['street']->getName());
    }

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
        $this->assertSame('username', $form->getChild('username')->getFieldName());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetChildFail()
    {
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

    /**
     * @expectedException BadMethodCallException
     */
    public function testOffsetSet()
    {
        $field = $this->createField('foo');
        $field['foo'] = 'bar';
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testOffsetUnset()
    {
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

    /**
     * @expectedException BadMethodCallException
     */
    public function testUndefinedExtensionMethod()
    {
        $field = $this->createField('foo');
        $field->foo();
    }

    /**
     * @expectedException RuntimeException
     */
    public function testInvalidExtensionMethod()
    {
        $field = $this->createField('foo', [
            new \Formz\Tests\Fixtures\FooExtension() ]);

        $field->baz();
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
}
