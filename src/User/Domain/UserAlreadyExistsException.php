<?php
declare(strict_types=1);

namespace App\User\Domain;

class UserAlreadyExistsException extends \Exception
{
    public $message = 'The user already exists.';
}
