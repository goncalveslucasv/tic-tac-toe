<?php

namespace App\Game\Domain;

class TiedGameException extends \Exception
{
    public $message = 'The game ended tied';
}