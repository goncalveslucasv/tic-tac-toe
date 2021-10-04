<?php
declare(strict_types=1);

namespace App\Game\Domain\Error;

class InvalidUserException extends \Exception
{
    public $message = 'The user can not play this game.';
}
