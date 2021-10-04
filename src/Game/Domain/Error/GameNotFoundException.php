<?php
declare(strict_types=1);

namespace App\Game\Domain\Error;

class GameNotFoundException extends \Exception
{
    public $message = 'The game you requested does not exist.';
}
