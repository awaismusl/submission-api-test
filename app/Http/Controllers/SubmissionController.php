<?php

namespace App\Http\Controllers;

use App\Facades\ApiResponse;
use App\Http\Requests\SubmissionRequest;
use App\Jobs\SubmissionJob;

class SubmissionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param SubmissionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubmissionRequest $request)
    {
        SubmissionJob::dispatch($request->all());

        return ApiResponse::success('Submission saved successfully');
    }
}
