<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Submission;

class ContactSubmissionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('files');
    }

    public function test_contact_submission_can_be_created()
    {
        $submission = Submission::factory()->make();
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
        $submission = Submission::factory()->make([
            'email' => 'john@example.com' // Use same email to ensure throttling works
        ])->toArray();

        for ($i = 0; $i < 6; $i++) {
            $this->postJson('/api/contact-submissions', $submission);
        }

        $response = $this->postJson('/api/contact-submissions', $submission);

        $response->assertStatus(429);
    }

    public function test_contact_with_gmail_email_is_not_allowed()
    {
        $submission = Submission::factory()->make([
            'email' => 'test@gmail.com',
        ]);

        $response = $this->postJson('/api/contact-submissions', $submission->toArray());

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'email',
        ]);
    }

    public function test_contact_submission_with_file_uploads()
    {
        $submission = Submission::factory()->make()->toArray();
        $submission['images'] = [
            UploadedFile::fake()->image('photo1.jpg'),
            UploadedFile::fake()->image('photo2.jpg')
        ];
        $submission['files'] = [
            UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf')
        ];

        $response = $this->postJson('/api/contact-submissions', $submission);

        $response->assertStatus(201);

        $submissionId = $response->json('data.id');

        $this->assertDatabaseCount('submission_images', 2);
        $this->assertDatabaseHas('submission_images', [
            'submission_id' => $submissionId,
            'original_filename' => 'photo1.jpg'
        ]);

        $this->assertDatabaseCount('submission_files', 1);
        $this->assertDatabaseHas('submission_files', [
            'submission_id' => $submissionId,
            'original_filename' => 'document.pdf'
        ]);
    }

    public function test_validates_image_types()
    {
        $submission = Submission::factory()->make()->toArray();
        $submission['images'] = [
            UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf')
        ];

        $response = $this->postJson('/api/contact-submissions', $submission);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['images.0']);
    }

    public function test_validates_file_types()
    {
        $submission = Submission::factory()->make()->toArray();
        $submission['files'] = [
            UploadedFile::fake()->image('photo.jpg')
        ];

        $response = $this->postJson('/api/contact-submissions', $submission);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['files.0']);
    }

    public function test_contact_submission_can_be_retrieved_on_response()
    {
        $submission = Submission::factory()->make()->toArray();
        $submission['images'] = [
            UploadedFile::fake()->image('photo1.jpg'),
            UploadedFile::fake()->image('photo2.jpg')
        ];
        $submission['files'] = [
            UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf')
        ];
        $response = $this->postJson("/api/contact-submissions", $submission);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'phone',
                'message',
                'street',
                'state',
                'zip',
                'country',
                'images',
                'files',
                'created_at',
                'updated_at',
            ],
        ]);
    }
}
