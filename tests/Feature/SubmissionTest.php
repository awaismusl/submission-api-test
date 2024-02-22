<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubmissionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test for submission.
     */
    public function test_that_store_submission_successful(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'message' => 'This is a test message.',
        ];

        $response = $this->postJson('/api/submit', $data);

        $response->assertSuccessful();
    }


    /**
     * A basic test for validation fails.
     */
    public function test_that_submission_failed_on_missing_required_data(): void
    {
        $data = [
            'name' => 'John Doe',
            'message' => 'This is a test message.',
        ];

        $response = $this->postJson('/api/submit', $data);

        $response->assertJsonValidationErrors('email');
    }
}
