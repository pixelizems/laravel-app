<?php

namespace Tests\Unit;

use App\Models\Submission;
use App\Services\ContactService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContactServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ContactService $contactService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->contactService = app(ContactService::class);
        Storage::fake('files');
    }

    public function test_it_creates_submission()
    {
        $submissionData = Submission::factory()->make()->toArray();

        $submission = $this->contactService->createSubmission($submissionData);

        $this->assertInstanceOf(Submission::class, $submission);
        $this->assertEquals($submissionData['name'], $submission->name);
        $this->assertEquals($submissionData['email'], $submission->email);
    }

    public function test_it_creates_submission_with_images()
    {
        $submissionData = Submission::factory()->make()->toArray();

        $images = [
            UploadedFile::fake()->image('photo1.jpg'),
            UploadedFile::fake()->image('photo2.jpg')
        ];

        $submission = $this->contactService->createSubmission($submissionData, $images);

        $this->assertCount(2, $submission->images);
        $this->assertEquals('photo1.jpg', $submission->images[0]->original_filename);
        $this->assertEquals('photo2.jpg', $submission->images[1]->original_filename);
    }

    public function test_it_creates_submission_with_files()
    {
        $submissionData = Submission::factory()->make()->toArray();

        $files = [
            UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf')
        ];

        $submission = $this->contactService->createSubmission($submissionData, [], $files);

        $this->assertCount(1, $submission->files);
        $this->assertEquals('document.pdf', $submission->files[0]->original_filename);
    }
}
