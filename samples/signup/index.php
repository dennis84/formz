<?php

require __DIR__.'/../../vendor/autoload.php';

class User
{
    private $username;
    private $password;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }
}

$builder = new \Formz\Builder();

$form = $builder->form([
    $builder->field('username')->nonEmptyText(),
    $builder->field('password', [
        $builder->field('main')->nonEmptyText(),
        $builder->field('confirm'),
    ])->verifying('Passwords don\'t match', function ($a, $b) {
        return $a === $b;
    }),
    $builder->field('accept')->boolean()->required(),
], function ($username, $passwords, $accept) {
    return new User($username, $passwords['main']);
}, function (User $user) {
    return ['username' => $user->getUsername()];
});

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
