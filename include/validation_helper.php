<?php

function hms_is_valid_contact(string $contact): bool
{
    return preg_match('/^\d{10}$/', $contact) === 1;
}

function hms_is_valid_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function hms_is_non_empty(string $value): bool
{
    return trim($value) !== '';
}
