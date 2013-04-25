<?php

require_once __DIR__ . '/../../vendor/autoload.php';

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

$builder = new \Formz\Builder();

$form = $builder->form([
    $builder->field('subject')->nonEmptyText(),
    $builder->field('message')->nonEmptyText()
], function ($subject, $message) {
    return new Contact($subject, $message);
}, function (Contact $contact) {
    return [
        'subject' => $contact->getSubject(),
        'message' => $contact->getMessage(),
    ];
});

if ('POST' == $_SERVER['REQUEST_METHOD']) {
    $form->bind($_POST);

    if ($form->isValid()) {
        print_r($form->getData());
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
