<?php namespace App\Repositories;

use App\Models\ActivityEvent;

class EventRepository {

    public function create(array $data) 
    {
        return ActivityEvent::create($data);
    }

    public function get() 
    {
        return ActivityEvent::all();
    }
}