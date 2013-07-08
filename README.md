# Formz

Formz is a simple form mapper for PHP. This library is based on the ScalaForms by Playframework.

[![Build Status](https://travis-ci.org/dennis84/formz.png?branch=master)](https://travis-ci.org/dennis84/formz)

## Simple Form

```php

<?php

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

$form->bind($_POST);

if ($form->isValid()) {
    // Save the data or something else ...
    $data = $form->getData();
}

// Render your template with the form ...

```

