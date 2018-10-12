<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateEventRequest;
use App\Event;

class EventController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $req) {
        return view('welcome');
    }

    public function create(CreateEventRequest $req) {
        $event = new Event;
        $this->requestToEvent($req, $event);

        $event->save();

        return view('welcome');
    }

    public function update(CreateEventRequest $req, Event $event) {
        $this->requestToEvent($req, $event);
        $event->save();

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
    }
}
