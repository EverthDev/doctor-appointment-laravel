<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');
        
        // Acepta 200 (OK) o 302 (redirección) como válidos
        $this->assertTrue(in_array($response->status(), [200, 302]), 
            "Expected status 200 or 302, got {$response->status()}");
    }
}