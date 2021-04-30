<?php

namespace App\Services;

use App\Repositories\EventRepository;

class EventService {

    protected $eventRepository;

    public function __construct()
    {
        $this->eventRepository = new EventRepository();
    }

    public function save(array $input) 
    {
        $input["date"] = date('Y-m-d', strtotime($input["date"]));

        $event = $this->eventRepository->create($input);

        if($event->repeat_week) {
            $event->end_date = date("Y-m-d", 
                strtotime("+".$event->interval_week." week", date('U', strtotime($event->date)))
            );
        }
        else {
            $event->end_date = $event->date;
        }
        
        return $event;
    }

    public function getAll()
    {
        $events = $this->eventRepository->get();

        $events = $events->map(function ($event) {
            
            if($event->repeat_week) {
                $event->end_date = date("Y-m-d", 
                    strtotime("+".$event->interval_week." week", date('U', strtotime($event->date)))
                );
            }
            else {
                $event->end_date = $event->date;
            }

            return $event;
        });

        return $events;
    }
}