<?php

namespace App\Services;

use App\Models\Submission;
use App\Models\SubmissionImage;
use App\Models\SubmissionFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContactService
{
    /**
     * Create a new contact submission with associated files.
     *
     * @param array $data The validated submission data
     * @param array $images The uploaded images
     * @param array $files The uploaded files
     * @return Submission
     */
    public function createSubmission(array $data, array $images = [], array $files = [])
    {
        return DB::transaction(function () use ($data, $images, $files) {
            // Create the submission
            $submission = Submission::create($data);

            // Process images
            $this->processImages($submission, $images);

            // Process files
            $this->processFiles($submission, $files);

            return $submission->load('images', 'files');
        });
    }

    /**
     * Process and store submission images.
     *
     * @param Submission $submission
     * @param array $images
     * @return void
     */
    private function processImages(Submission $submission, array $images)
    {
        foreach ($images as $image) {
            if ($image instanceof UploadedFile) {
                try {
                    $path = $image->store('images', 'files');

                    SubmissionImage::create([
                        'submission_id' => $submission->id,
                        'path' => $path,
                        'original_filename' => $image->getClientOriginalName(),
                        'mime_type' => $image->getMimeType(),
                        'size' => $image->getSize()
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to process image upload: ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * Process and store submission files.
     *
     * @param Submission $submission
     * @param array $files
     * @return void
     */
    private function processFiles(Submission $submission, array $files)
    {
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                try {
                    $path = $file->store('files', 'files');

                    SubmissionFile::create([
                        'submission_id' => $submission->id,
                        'path' => $path,
                        'original_filename' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize()
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to process file upload: ' . $e->getMessage());
                }
            }
        }
    }
}
