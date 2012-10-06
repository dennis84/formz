<?php

ini_set('display_errors', 1);

require_once __DIR__.'/../vendor/autoload.php';

use Rucola\Rucola;

$rucula = new Rucola();
$form = $rucula->mapping(array(
    'email'   => $rucula->type('non_empty_text'),
    'message' => $rucula->type('text'),
));

if ('POST' == $_SERVER['REQUEST_METHOD']) {
    $form->bind($_POST);
    $form->fold(function ($formWithErrors) {
        echo render('contact.php.html', array(
            'form' => $formWithErrors,
        ));
    }, function ($formData) {
        die('sadj');
    });
} else {
    echo render('contact.php.html', array(
        'form' => $form,
    ));
}

function render($template, array $params = array())
{
    ob_start();
    extract($params);
    include $template;
    return ob_get_clean();
}
