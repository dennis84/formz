<?php

require_once __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors', 1);

class Contact
{
    protected $subject;
    protected $message;

    public function __construct($subject, $message)
    {
        $this->subject = $subject;
        $this->message = $message;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getMessage()
    {
        return $this->message;
    }
}

$rucola = new \Rucola\Rucola();

$form = $rucola->form(array(
    $rucola->field('subject')->nonEmptyText(),
    $rucola->field('message')->nonEmptyText()
), function ($subject, $message) {
    return new Contact($subject, $message);
}, function (Contact $contact) {
    return array(
        'subject' => $contact->getSubject(),
        'message' => $contact->getMessage(),
    );
});

if ('POST' == $_SERVER['REQUEST_METHOD']) {
    $form->bind($_POST);

    $form->fold(function ($formWithErrors) {
        echo render('form.php.html', array(
            'form' => $formWithErrors,
        ));
    }, function ($formData) {
        print_r($formData);
    });
} else {
    echo render('form.php.html', array(
        'form' => $form,
    ));
}

function render($template, array $parameters = array())
{
    ob_start();
    extract($parameters);
    include __DIR__ . '/' . $template;
    return ob_get_clean();
}
