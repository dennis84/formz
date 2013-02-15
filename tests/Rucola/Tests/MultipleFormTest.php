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
        $form = $rucola->form([
            $rucola->field('choices')->multiple(),
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

    public function testPassEmpty()
    {
        $rucola = new Rucola();
        $form = $rucola->form([
            $rucola->field('choices')->multiple(),
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

    public function testPassNothing()
    {
        $rucola = new Rucola();
        $form = $rucola->form([
            $rucola->field('choices')->multiple(),
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

    public function testPassNothingAppliedToModel()
    {
        $rucola = new Rucola();

        $form = $rucola->form([
            $rucola->field('title'),
            $rucola->field('tags')->multiple(),
            $rucola->embed('attributes', [
                $rucola->field('name'),
                $rucola->field('name'),
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

    public function testPassNested()
    {
        $rucola = new Rucola();
        $form = $rucola->form([
            $rucola->embed('choices', [
                $rucola->field('key'),
                $rucola->field('value'),
            ])->multiple(),
        ]);

        $data = [
            'choices' => [
                [
                    'key' => 'foo',
                    'value' => 'bar'
                ],
                [
                    'key' => 'bla',
                    'value' => 'blubb'
                ],
            ],
        ];

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) use ($data) {
            $this->assertEquals($formData, $data);
        });
    }

    public function testPassNestedAppliedToObject()
    {
        $rucola = new Rucola();
        $form = $rucola->form([
            $rucola->embed('choices', [
                $rucola->field('name'),
                $rucola->field('value'),
            ], function ($name, $value) {
                return new Attribute($name, $value);
            })->multiple(),
        ]);

        $data = [
            'choices' => [
                [
                    'name' => 'foo',
                    'value' => 'bar'
                ],
                [
                    'name' => 'bla',
                    'value' => 'blubb'
                ],
            ],
        ];

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) use ($data) {
            $this->assertInstanceOf('Rucola\Tests\Model\Attribute', $formData['choices'][0]);
            $this->assertInstanceOf('Rucola\Tests\Model\Attribute', $formData['choices'][1]);
        });
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNonArrayValueToMultipleType()
    {
        $rucola = new Rucola();
        $form = $rucola->form([
            $rucola->field('choices')->multiple(),
        ]);

        $form->bind([
            'choices' => 'foo',
        ]);
    }
}
