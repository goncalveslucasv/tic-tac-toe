<?php
declare(strict_types=1);

namespace App\Game\Domain;

class GameNotFoundException extends \Exception
{
    public $message = 'The game you requested does not exist.';
}
