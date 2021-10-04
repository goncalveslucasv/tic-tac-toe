<?php
declare(strict_types=1);

namespace App\User\Domain\Error;

class UserAlreadyExistsException extends \Exception
{
    public $message = 'The user already exists.';
}
