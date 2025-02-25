<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubmissionResource;
use App\Models\Submission;
use App\Http\Requests\ContactSubmissionRequest;
use Illuminate\Support\Facades\Storage;

class ContactSubmissionController extends Controller
{
    /**
     * Store a new contact submission.
     *
     * @param  \App\Http\Requests\ContactSubmissionRequest  $request
     * @return \App\Http\Resources\SubmissionResource
     */
    public function store(ContactSubmissionRequest $request)
    {
        // Create the submission
        $submission = Submission::create($request->validated());

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'files');
            }
        }

        // Handle file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('files', 'files');
            }
        }

        return new SubmissionResource($submission);
    }
}
