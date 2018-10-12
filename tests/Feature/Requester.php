<?php

namespace Tests\Feature;

class Requester {

    private $testCase;

    public function __construct($testCase) {
        $this->testCase = $testCase;
    }

    public function postRequest($actingAs, $url, $reqParams) {
        return $this->testCase->actingAs($actingAs)
                         ->post($url, $reqParams);
    }

    public function getRequest($actingAs, $url, $reqParams) {
        return $this->testCase->actingAs($actingAs)
                         ->get($url, $reqParams);
    }


}