<?php

namespace App\Game\Domain\Error;

class TiedGameException extends \Exception
{
    public $message = 'The game ended tied';
}