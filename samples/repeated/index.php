<?php

$loader = require __DIR__.'/../../vendor/autoload.php';
$loader->add('Formz\Tests', __DIR__ . '/../../tests');

use Formz\Tests\Fixtures\Post;
use Formz\Tests\Fixtures\Attribute;

$builder = new \Formz\Builder();

$form = $builder->form([
    $builder->field('title')->nonEmptyText(),
    $builder->field('tags')->nonEmptyText()->multiple(),
    $builder->embed('attributes', [
        $builder->field('name')->nonEmptyText(),
        $builder->field('value')->nonEmptyText(),
    ], function ($name, $value) {
        return new Attribute($name, $value);
    }, function (Attribute $attribute) {
        return [
            'name' => $attribute->getName(),
            'value' => $attribute->getValue(),
        ];
    })->multiple(),
], function ($title, array $tags, array $attributes) {
    return new Post($title, $tags, $attributes);
}, function (Post $post) {
    return [
        'title' => $post->getTitle(),
        'tags' => $post->getTags(),
        'attributes' => $post->getAttributes(),
    ];
});

$post = new Post('Hello World', [ 'foo', 'bar' ], [
    new Attribute('foo', 'bar'),
    new Attribute('baz', 'biz'),
]);

$form->fill($post);

if ('POST' == $_SERVER['REQUEST_METHOD']) {
    $form->bind($_POST);

    if ($form->isValid()) {
        echo '<pre>' . print_r($form->getData(), true) . '</pre>';
    }
}

echo render('form.php.html', ['form' => $form]);

function render($template, array $parameters = [])
{
    ob_start();
    extract($parameters);
    include __DIR__ . '/' . $template;
    return ob_get_clean();
}
