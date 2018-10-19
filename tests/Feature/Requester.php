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

    public function putRequest($actingAs, $url, $reqParams) {
        return $this->testCase->actingAs($actingAs)
                         ->put($url, $reqParams);
    }

    public function deleteRequest($actingAs, $url, $reqParams) {
        return $this->testCase->actingAs($actingAs)
                         ->delete($url, $reqParams);
    }


}