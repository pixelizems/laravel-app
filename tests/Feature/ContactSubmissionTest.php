<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Submission;

class ContactSubmissionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_contact_submission_can_be_created()
    {

        $submission = Submission::factory()->create();
        $response = $this->postJson('/api/contact-submissions', $submission->toArray());

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'data' => [
                'name',
                'email',
                'phone',
                'message',
                'street',
                'state',
                'zip',
                'country',
            ],
        ]);
    }

    public function test_contact_submission_validation_errors()
    {
        $response = $this->postJson('/api/contact-submissions', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'name',
            'email',
            'phone',
            'message',
            'street',
            'state',
            'zip',
            'country',
        ]);
    }

    public function test_contact_submission_throttling()
    {
        for ($i = 0; $i < 6; $i++) {
            $this->postJson('/api/contact-submissions', [
                'name' => $this->faker->name,
                'email' => $this->faker->email,
                'phone' => $this->faker->phoneNumber,
                'message' => $this->faker->sentence,
                'street' => $this->faker->streetAddress,
                'state' => $this->faker->state,
                'zip' => $this->faker->postcode,
                'country' => $this->faker->country,
            ]);
        }

        $response = $this->postJson('/api/contact-submissions', [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'message' => $this->faker->sentence,
            'street' => $this->faker->streetAddress,
            'state' => $this->faker->state,
            'zip' => $this->faker->postcode,
            'country' => $this->faker->country,
        ]);

        $response->assertStatus(429);
    }

    public function test_contact_with_gmail_email_is_not_allowed()
    {
        $submission = Submission::factory()->create([
            'email' => 'test@gmail.com',
        ]);

        $response = $this->postJson('/api/contact-submissions', $submission->toArray());

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'email',
        ]);
    }
}
