<?php

namespace Rucola\Tests;

use Rucola\Rucola;
use Rucola\Tests\Model\Post;
use Rucola\Tests\Model\Attribute;

class MultipleFormTest extends \PHPUnit_Framework_TestCase
{
    public function testPass()
    {
        $rucola = new Rucola();
        $form = $rucola->form(array(
            $rucola->field('choices')->multiple(),
        ));

        $form->bind(array(
            'choices' => array('foo', 'bar', 'baz'),
        ));

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) {
            $this->assertEquals($formData, array(
                'choices' => array('foo', 'bar', 'baz'),
            ));
        });
    }

    public function testPassEmpty()
    {
        $rucola = new Rucola();
        $form = $rucola->form(array(
            $rucola->field('choices')->multiple(),
        ));

        $form->bind(array(
            'choices' => array(),
        ));

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) {
            $this->assertEquals($formData, array(
                'choices' => array(),
            ));
        });
    }

    public function testPassNothing()
    {
        $rucola = new Rucola();
        $form = $rucola->form(array(
            $rucola->field('choices')->multiple(),
        ));

        $form->bind(array());

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) {
            $this->assertEquals($formData, array(
                'choices' => array(),
            ));
        });
    }

    public function testPassNothingAppliedToModel()
    {
        $rucola = new Rucola();

        $form = $rucola->form(array(
            $rucola->field('title'),
            $rucola->field('tags')->multiple(),
            $rucola->embed('attributes', array(
                $rucola->field('name'),
                $rucola->field('name'),
            ), function ($name, $value) {
                return new Attribute($name, $value);
            }, function (Attribute $attr) {
                return array(
                    'name' => $attr->getName(),
                    'value' => $attr->getName(),
                );
            })->multiple(),
        ), function ($title, array $tags, array $attrs) {
            return new Post($title, $tags, $attrs);
        }, function (Post $post) {
            return array(
                'title' => $post->getTitle(),
                'tags' => $post->getTags(),
                'attributes' => $post->getAttributes(),
            );
        });

        $form->bind(array());

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) {
            $this->assertNull($formData->getTitle());
            $this->assertSame(array(), $formData->getTags());
            $this->assertSame(array(), $formData->getAttributes());
        });
    }

    public function testPassNested()
    {
        $rucola = new Rucola();
        $form = $rucola->form(array(
            $rucola->embed('choices', array(
                $rucola->field('key'),
                $rucola->field('value'),
            ))->multiple(),
        ));

        $data = array(
            'choices' => array(
                array(
                    'key' => 'foo',
                    'value' => 'bar'
                ),
                array(
                    'key' => 'bla',
                    'value' => 'blubb'
                ),
            ),
        );

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) use ($data) {
            $this->assertEquals($formData, $data);
        });
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNonArrayValueToMultipleType()
    {
        $rucola = new Rucola();
        $form = $rucola->form(array(
            $rucola->field('choices')->multiple(),
        ));

        $form->bind(array(
            'choices' => 'foo',
        ));
    }
}
