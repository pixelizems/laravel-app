<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubmissionResource;
use App\Http\Requests\ContactSubmissionRequest;
use App\Services\ContactService;

class ContactSubmissionController extends Controller
{
    /**
     * The contact service instance.
     *
     * @var \App\Services\ContactService
     */
    protected $contactService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Services\ContactService $contactService
     * @return void
     */
    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Store a new contact submission.
     *
     * @param  \App\Http\Requests\ContactSubmissionRequest  $request
     * @return \App\Http\Resources\SubmissionResource
     */
    public function store(ContactSubmissionRequest $request)
    {
        $submission = $this->contactService->createSubmission(
            $request->validated(),
            $request->file('images') ?? [],
            $request->file('files') ?? []
        );

        if ($submission) {
            $submission->load('images', 'files');
            return new SubmissionResource($submission);
        }

        return response()->json(['message' => 'Failed to create submission'], 500);
    }
}
