<?php

namespace Formz\Tests;

use Formz\Builder;
use Formz\Tests\Model\Post;
use Formz\Tests\Model\Attribute;

class MultipleFormTest extends \PHPUnit_Framework_TestCase
{
    public function test_bind_and_pass()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('choices')->multiple(),
        ]);

        $form->bind([
            'choices' => ['foo', 'bar', 'baz'],
        ]);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) {
            $this->assertEquals($formData, [
                'choices' => ['foo', 'bar', 'baz'],
            ]);
        });
    }

    public function test_bind_and_pass_empty()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('choices')->multiple(),
        ]);

        $form->bind([
            'choices' => [],
        ]);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) {
            $this->assertEquals($formData, [
                'choices' => [],
            ]);
        });
    }

    public function test_bind_and_pass_with_nothing()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('choices')->multiple(),
        ]);

        $form->bind([]);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) {
            $this->assertEquals($formData, [
                'choices' => [],
            ]);
        });
    }

    public function test_bind_and_pass_with_nothing_applied_to_object()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('title'),
            $builder->field('tags')->multiple(),
            $builder->embed('attributes', [
                $builder->field('name'),
                $builder->field('name'),
            ], function ($name, $value) {
                return new Attribute($name, $value);
            }, function (Attribute $attr) {
                return [
                    'name' => $attr->getName(),
                    'value' => $attr->getName(),
                ];
            })->multiple(),
        ], function ($title, array $tags, array $attrs) {
            return new Post($title, $tags, $attrs);
        }, function (Post $post) {
            return [
                'title' => $post->getTitle(),
                'tags' => $post->getTags(),
                'attributes' => $post->getAttributes(),
            ];
        });

        $form->bind([]);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) {
            $this->assertNull($formData->getTitle());
            $this->assertSame([], $formData->getTags());
            $this->assertSame([], $formData->getAttributes());
        });
    }

    public function test_bind_and_pass_nested_applied_to_array()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->embed('choices', [
                $builder->field('key'),
                $builder->field('value'),
            ])->multiple(),
        ]);

        $data = [
            'choices' => [
                ['key' => 'foo', 'value' => 'bar'],
                ['key' => 'bla', 'value' => 'blubb'],
            ],
        ];

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) use ($data) {
            $this->assertEquals($formData, $data);
        });
    }

    public function test_bind_and_pass_nested_applied_to_object()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->embed('choices', [
                $builder->field('name'),
                $builder->field('value'),
            ], function ($name, $value) {
                return new Attribute($name, $value);
            })->multiple(),
        ]);

        $data = [
            'choices' => [
                ['name' => 'foo', 'value' => 'bar'],
                ['name' => 'bla', 'value' => 'blubb'],
            ],
        ];

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) use ($data) {
            $this->assertInstanceOf('Formz\Tests\Model\Attribute', $formData['choices'][0]);
            $this->assertInstanceOf('Formz\Tests\Model\Attribute', $formData['choices'][1]);
        });
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_bind_and_fail_with_non_array_value()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('choices')->multiple(),
        ]);

        $form->bind([
            'choices' => 'foo',
        ]);
    }

    public function test_bind_and_getName()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->embed('choices', [
                $builder->field('key'),
                $builder->field('value'),
            ])->multiple(),
        ]);

        $this->assertEquals('choices[key]', $form['choices']['key']->getName());
        $this->assertEquals('choices[value]', $form['choices']['value']->getName());

        $data = [
            'choices' => [
                ['key' => 'foo', 'value' => 'bar'],
                ['key' => 'bla', 'value' => 'blubb'],
            ],
        ];

        $form->bind($data);

        $this->assertEquals('choices[0][key]', $form['choices']['0']['key']->getName());
        $this->assertEquals('choices[0][value]', $form['choices']['0']['value']->getName());
        $this->assertEquals('choices[1][key]', $form['choices']['1']['key']->getName());
        $this->assertEquals('choices[1][value]', $form['choices']['1']['value']->getName());
    }

    public function test_fill_flat_form_unapplied_from_array()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('choices')->multiple(),
        ]);

        $form->fill([
            'choices' => ['foo', 'bar', 'baz'],
        ]);

        $this->assertEquals('foo', $form['choices']['0']->getValue());
        $this->assertEquals('bar', $form['choices']['1']->getValue());
        $this->assertEquals('baz', $form['choices']['2']->getValue());
    }

    public function test_fill_nested_form_unapplied_from_array()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->embed('choices', [
                $builder->field('key'),
                $builder->field('value'),
            ])->multiple(),
        ]);

        $form->fill([
            'choices' => [
                ['key' => 'foo', 'value' => 'bar'],
                ['key' => 'bla', 'value' => 'blubb'],
            ],
        ]);

        $this->assertEquals('foo', $form['choices']['0']['key']->getValue());
        $this->assertEquals('bar', $form['choices']['0']['value']->getValue());
        $this->assertEquals('bla', $form['choices']['1']['key']->getValue());
        $this->assertEquals('blubb', $form['choices']['1']['value']->getValue());
    }
}