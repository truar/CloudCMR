<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Http\Requests\CreateMemberRequest;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateMemberRequestTest extends TestCase
{

    public function setUp() {
        parent::setUp();
        $this->rules     = (new CreateMemberRequest())->rules();
        $this->validator = $this->app['validator'];
    }

    /** @test */
    public function validLastName() {
        $field = "lastname";
        $this->assertTrue($this->validateField($field, 'jon'));
        $this->assertTrue($this->validateField($field, 'jo'));
        $this->assertTrue($this->validateField($field, 'j'));
        $this->assertFalse($this->validateField($field, ''));
    }

    /** @test */
    public function validFirstName() {
        $field = "firstname";
        $this->assertTrue($this->validateField($field, 'jon'));
        $this->assertTrue($this->validateField($field, 'jo'));
        $this->assertTrue($this->validateField($field, 'j'));
        $this->assertFalse($this->validateField($field, ''));
    }

    /** @test */
    public function validEmail() {
        $field = "email";
        $this->assertTrue($this->validateField($field, 'jon@mail.com'));
        $this->assertTrue($this->validateField($field, 'jon.doe@mail.com'));
        $this->assertTrue($this->validateField($field, 'jon-doe@mail.com'));
        $this->assertTrue($this->validateField($field, 'JON@mail.com'));
        $this->assertFalse($this->validateField($field, ''));
        $this->assertFalse($this->validateField($field, 'jon'));
    }

    /** @test */
    public function validBirthDate() {
        $field = "birthdate";
        //$this->assertTrue($this->validateField($field, '23/11/1992'));
        $this->assertFalse($this->validateField($field, '23-11-1992'));
        $this->assertFalse($this->validateField($field, ''));
        $this->assertFalse($this->validateField($field, '23'));
    }

    protected function getFieldValidator($field, $value) {
        return $this->validator->make(
            [$field => $value], 
            [$field => $this->rules[$field]]
        );
    }

    protected function validateField($field, $value) {
        return $this->getFieldValidator($field, $value)->passes();
    }
}
