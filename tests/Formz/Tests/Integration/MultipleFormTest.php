<?php

namespace Formz\Tests\Integration;

use Formz\Builder;
use Formz\Tests\Fixtures\Post;
use Formz\Tests\Fixtures\Attribute;

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
        $formData = $form->getData();

        $this->assertSame($formData, [
            'choices' => ['foo', 'bar', 'baz'],
        ]);

        $this->assertSame('choices[]', $form['choices']->getName());
    }

    public function test_bind_and_pass_empty()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('choices')->multiple(),
        ]);

        $form->bind(['choices' => []]);
        $formData = $form->getData();

        $this->assertSame($formData, ['choices' => []]);
    }

    public function test_bind_and_pass_with_nothing()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('choices')->multiple(),
        ]);

        $form->bind([]);
        $formData = $form->getData();

        $this->assertSame($formData, ['choices' => []]);
    }

    public function test_bind_with_constraints()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('choices')->nonEmptyText()->multiple(),
        ]);

        $form->bind([
            'choices' => ['foo', 'bar', 'baz'],
        ]);

        $formData = $form->getData();
        $this->assertTrue($form->isValid());
    }

    public function test_bind_with_failing_constraints()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('choices')->nonEmptyText()->multiple(),
        ]);

        $form->bind([
            'choices' => [''],
        ]);

        $this->assertFalse($form->isValid());
    }

    public function test_bind_and_pass_with_nothing_applied_to_object()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('title'),
            $builder->field('tags')->multiple(),
            $builder->field('attributes', [
                $builder->field('name'),
                $builder->field('value'),
            ], function ($name, $value) {
                return new Attribute($name, $value);
            })->multiple(),
        ], function ($title, array $tags, array $attrs) {
            return new Post($title, $tags, $attrs);
        });

        $form->bind([]);
        $formData = $form->getData();

        $this->assertNull($formData->getTitle());
        $this->assertSame([], $formData->getTags());
        $this->assertSame([], $formData->getAttributes());
    }

    public function test_bind_and_pass_nested_applied_to_array()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('choices', [
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
        $formData = $form->getData();

        $this->assertSame($formData, $data);
    }

    public function test_bind_and_pass_nested_applied_to_object()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('choices', [
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
        $formData = $form->getData();

        $this->assertInstanceOf('Formz\Tests\Fixtures\Attribute', $formData['choices'][0]);
        $this->assertInstanceOf('Formz\Tests\Fixtures\Attribute', $formData['choices'][1]);
        $this->assertSame('foo', $formData['choices'][0]->getName());
        $this->assertSame('bla', $formData['choices'][1]->getName());
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

        $form->bind(['choices' => 'foo']);
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

        $this->assertSame('foo', $form['choices']['0']->getValue());
        $this->assertSame('bar', $form['choices']['1']->getValue());
        $this->assertSame('baz', $form['choices']['2']->getValue());
    }

    public function test_fill_nested_form_unapplied_from_array()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('choices', [
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

        $this->assertSame('foo', $form['choices']['0']['key']->getValue());
        $this->assertSame('bar', $form['choices']['0']['value']->getValue());
        $this->assertSame('bla', $form['choices']['1']['key']->getValue());
        $this->assertSame('blubb', $form['choices']['1']['value']->getValue());
    }

    public function test_fill_unapplied_from_object()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('title'),
            $builder->field('tags')->multiple(),
            $builder->field('attributes', [
                $builder->field('name'),
                $builder->field('value'),
            ], null, function (Attribute $attr) {
                return [
                    'name' => $attr->getName(),
                    'value' => $attr->getValue(),
                ];
            })->multiple(),
        ], null, function (Post $post) {
            return [
                'title' => $post->getTitle(),
                'tags' => $post->getTags(),
                'attributes' => $post->getAttributes(),
            ];
        });

        $post = new Post('Foo', ['foo', 'bar', 'baz'], [
            new Attribute('bla', 'blubb'),
            new Attribute('hello', 'world'),
        ]);

        $form->fill($post);

        $this->assertSame('foo', $form['tags']['0']->getValue());
        $this->assertSame('bar', $form['tags']['1']->getValue());
        $this->assertSame('bla', $form['attributes']['0']['name']->getValue());
        $this->assertSame('blubb', $form['attributes']['0']['value']->getValue());
        $this->assertSame('hello', $form['attributes']['1']['name']->getValue());
        $this->assertSame('world', $form['attributes']['1']['value']->getValue());
    }

    public function test_verify_multiple_value()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('emails')->verifying('email', function ($value) {
                return (boolean) filter_var($value, FILTER_VALIDATE_EMAIL);
            })->multiple(),
        ]);

        $form->bind([
            'emails' => ['foo@bar.de', 'blah@blub.de'],
        ]);

        $this->assertTrue($form->isValid());
    }

    public function test_verify_multiple_value_fail()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('emails')->verifying('email', function ($value) {
                return (boolean) filter_var($value, FILTER_VALIDATE_EMAIL);
            })->multiple(),
        ]);

        $form->bind([
            'emails' => ['foo@bar.de', 'blah'],
        ]);

        $this->assertFalse($form->isValid());
    }

    public function test_fill_and_bind_and_decrease_field()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('choices')->multiple(),
        ]);

        $form->fill([
            'choices' => ['foo', 'bar', 'baz'],
        ]);

        $this->assertSame('foo', $form['choices']['0']->getValue());
        $this->assertSame('bar', $form['choices']['1']->getValue());
        $this->assertSame('baz', $form['choices']['2']->getValue());

        $form->bind([ 'choices' => ['foo', 'bar'] ]);

        $this->assertSame($form->getData(), [
            'choices' => ['foo', 'bar'],
        ]);
    }

    public function test_fill_and_bind_and_increase_field()
    {
        $builder = new Builder();
        $form = $builder->form([
            $builder->field('choices')->multiple(),
        ]);

        $form->fill([
            'choices' => ['foo', 'bar'],
        ]);

        $this->assertSame('foo', $form['choices']['0']->getValue());
        $this->assertSame('bar', $form['choices']['1']->getValue());

        $form->bind([ 'choices' => ['foo', 'bar', 'baz'] ]);

        $this->assertSame($form->getData(), [
            'choices' => ['foo', 'bar', 'baz'],
        ]);
    }
}
