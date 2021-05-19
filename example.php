<?php

require __DIR__ . '/UmblerApi/Email.php';

$umblerMail = new UmblerApi\Email;

$umblerMail->debug = false;
$umblerMail->setCredentials('CLIENT_ID', 'API_TOKEN');
$umblerMail->setDomain('yourdomain.com');

$emails = $umblerMail->getEmails();
print_r($emails);
