<?php

namespace App\Common;

class AgregateRoot
{
    protected $events = [];

    protected function apply($event)
    {
        array_push($this->events, $event);
    }

    public function events(){
        if(!empty($this->events[0])) {
            return [$this->events[0], get_class($this->events[0])::NAME];
            }

        return [];
        }
}