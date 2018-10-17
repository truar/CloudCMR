<?php

namespace CloudCMR\Http\Controllers;

use Illuminate\Http\Request;
use CloudCMR\Http\Requests\CreateEventRequest;
use CloudCMR\Event;
use CloudCMR\Transportation;

class EventController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    public function index(Request $req) {
        return view('welcome');
    }

    public function create(CreateEventRequest $req) {
        $event = new Event;
        $this->requestToEvent($req, $event);

        $event->save();
        $event->saveTransportations();
        return view('welcome');
    }

    public function update(CreateEventRequest $req, Event $event) {
        $this->requestToEvent($req, $event);
        $event->save();
        $event->saveTransportations();
        return view('welcome');
    }

    public function delete(Event $event) {
        $event->delete();
        return view('welcome');
    }

    private function requestToEvent(Request $req, Event $event) {
        $event->name = $req->name;
        $event->startDate = $req->startDate;
        $event->price = $req->price;
        $event->type = $req->type;

        if(isset($req->transportations)) {
            $index = 0;
            foreach($req->transportations as $transportation) {
                if(!isset($event->transportations[$index])) {
                    $event->transportations[$index] = new Transportation;
                }
                $event->transportations[$index]->type = $transportation['type'];
                $event->transportations[$index]->departureDate = $transportation['departureDate'];
                $event->transportations[$index]->arrivalDate = $transportation['arrivalDate'];
                $event->transportations[$index]->departureLocation = $transportation['departureLocation'];
                $event->transportations[$index++]->arrivalLocation = $transportation['arrivalLocation'];
            }
        }
    }
}
