<?php

namespace Tests\Feature;

use CloudCMR\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp()
    {
        /**
         * This disables the exception handling to display the stacktrace on the console
         * the same way as it shown on the browser
         */
        parent::setUp();
        $this->dbType = env('DB_CONNECTION', 'sqlite');
        $this->requester = new Requester($this);
        $this->user = factory(User::class)->create();
        $this->event = factory(\CloudCMR\Event::class)->make();
        $this->transportation = factory(\CloudCMR\Transportation::class)->make();
        //$this->withoutExceptionHandling();
    }
    
    /**
     * Fail to access members without authorization
     */
    public function test_it_cant_access_without_authorization() {
        $response = $this->get('/events');
        $response->assertStatus(302);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_can_create_an_event() {
        $response = $this->requester->postRequest($this->user, '/events/create', $this->event->toArray());
        $this->assertOkAndHas($response, $this->event);
    }

    public function test_it_cant_create_an_event_without_name() {
        $this->event->name = null;
        $response = $this->requester->postRequest($this->user, '/events/create', $this->event->toArray());
        $this->assertErrorFormAndMissing($response, $this->event);
    }

    public function test_it_cant_create_an_event_without_startDate() {
        $this->event->startDate = null;
        $response = $this->requester->postRequest($this->user, '/events/create', $this->event->toArray());
        $this->assertErrorFormAndMissing($response, $this->event);
    }

    public function test_it_cant_create_an_event_without_type() {
        $this->event->type = null;
        $response = $this->requester->postRequest($this->user, '/events/create', $this->event->toArray());
        $this->assertErrorFormAndMissing($response, $this->event);
    }

    public function test_it_cant_create_an_event_with_a_wrong_type() {
        $this->event->type = 'TOTO';
        $response = $this->requester->postRequest($this->user, '/events/create', $this->event->toArray());
        $this->assertErrorFormAndMissing($response, $this->event);
    }

    public function test_it_cant_create_an_event_without_price() {
        $this->event->price = null;
        $response = $this->requester->postRequest($this->user, '/events/create', $this->event->toArray());
        $this->assertErrorFormAndMissing($response, $this->event);
    }

    public function test_it_cant_create_an_event_with_a_price_not_number() {
        $this->event->price = 'abc';
        $response = $this->requester->postRequest($this->user, '/events/create', $this->event->toArray());
        $this->assertErrorFormAndMissing($response, $this->event);
    }

    public function test_it_cant_create_an_event_with_a_price_lower_than_zero() {
        $this->event->price = -5;
        $response = $this->requester->postRequest($this->user, '/events/create', $this->event->toArray());
        $this->assertErrorFormAndMissing($response, $this->event);
    }

    public function test_it_can_update_an_event() {
        $this->event->save();
        $this->event->name = 'New Name';

        $response = $this->requester->postRequest($this->user, '/events/update/' . $this->event->id, $this->event->toArray());
        $this->assertOkAndHas($response, $this->event);
    }

    public function test_it_cant_update_a_not_existing_event() {
        $response = $this->requester->postRequest($this->user, '/events/update/1', $this->event->toArray());
        $response->assertStatus(404);
    }

    public function test_it_can_delete_an_event() {
        $this->event->save();

        $response = $this->requester->getRequest($this->user, '/events/delete/' . $this->event->id, $this->event->toArray());
        $this->assertOkAndMissing($response, $this->event);
    }
    
    public function test_it_cant_delete_a_not_existing_event() {
        $response = $this->requester->getRequest($this->user, '/events/delete/1', $this->event->toArray());
        $response->assertStatus(404);
    }

    public function test_it_can_create_an_event_with_transportation() {
        $this->event->transportations = [$this->transportation->toArray()];
        $response = $this->requester->postRequest($this->user, '/events/create', $this->event->toArray());
        
        unset($this->event->transportations);
        $this->assertOkAndHas($response, $this->event);
        $this->assertDatabaseHas('transportations', $this->transportation->toArray());
    }

    public function test_it_can_update_an_event_transportations() {
        $this->event->save();
        $this->event->transportations = [$this->transportation];
        $this->event->saveTransportations();

        $this->transportation->departureDate = '1992-11-21 10:11:01';
        $this->event->transportations = [$this->transportation->toArray()];

        $response = $this->requester->postRequest($this->user, '/events/update/' . $this->event->id, $this->event->toArray());
        
        unset($this->event->transportations);
        $this->assertOkAndHas($response, $this->event);
        $this->assertDatabaseHas('transportations', $this->transportation->toArray()); 
    }

    private function assertErrorFormAndMissing($response, $event) {
        $response->assertStatus(302);
        $this->assertDatabaseMissing('events', $event->toArray());
    }

    private function assertOkAndHas($response, $event) {
        $response->assertStatus(200);
        $this->assertDatabaseHas('events', $event->toArray());
    }

    private function assertOkAndMissing($response, $event) {
        $response->assertStatus(200);
        $this->assertDatabaseMissing('events', $event->toArray());
    }
    
}
