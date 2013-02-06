<?php

require_once __DIR__ . '/../../vendor/autoload.php';

class Post
{
    protected $tags = [];
    protected $attributes = [];

    public function __construct(array $tags, array $attributes)
    {
        $this->tags = $tags;
        $this->attributes = $attributes;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }
}

$rucola = new \Rucola\Rucola();

$form = $rucola->form([
    $rucola->field('tags')->nonEmptyText()->multiple(),
    $rucola->embed('attributes', [
        $rucola->field('name')->nonEmptyText(),
        $rucola->field('value')->nonEmptyText(),
    ])->multiple(),
], function (array $tags, array $attributes) {
    return new Post($tags, $attributes);
}, function (Post $post) {
    return [
        'tags' => $post->getTags(),
        'attributes' => $post->getAttributes(),
    ];
});

if ('POST' == $_SERVER['REQUEST_METHOD']) {
    $form->bind($_POST);

    $form->fold(function ($formWithErrors) {
        echo render('form.php.html', [
            'form' => $formWithErrors,
        ]);
    }, function ($formData) {
        print_r($formData);
    });
} else {
    echo render('form.php.html', [
        'form' => $form,
    ]);
}

function render($template, array $parameters = [])
{
    ob_start();
    extract($parameters);
    include __DIR__ . '/' . $template;
    return ob_get_clean();
}
