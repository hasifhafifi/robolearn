@extends('layouts.app')

@section('content')
@if(isset($errorMessage))
    <div class="alert alert-danger">{{ $errorMessage }}</div>
@endif

<div class="pagetitle">
    <h1>Edit Submission</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('submissionIndex') }}">Submissions</a></li>
        <li class="breadcrumb-item active">{{ $submission->submissionName }}</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">{{ $submission->submissionName }}</h5>
            <div class="p-3 mb-2 bg-light rounded">
                Submission Description: {!! $submission->submissionDesc !!}
                
                Submission Type:
                @if($submission->submissionType == 'allfile')
                All File
                @else
                {{ $submission->submissionType }}
                @endif
                <br><br>
                Submitted File:  <a href="{{ route('getSubmission', ['id' => $submissionFile->id]) }}">{{ $submissionFile->submittedFileName }}</a>
            </div>
            <form action="{{ route('editSubmittedFile') }}" enctype="multipart/form-data" method="POST">
                @csrf
                {{-- <div class="dropzone" id="myDropzone"></div> --}}
                {{-- <button class="btn btn-primary" type="submit" id="submitButton">Submit</button> --}}
                <div class="form-group mb-3">
                    <label for="filesubmit">Add File:</label>
                    <input type="file" name="filesubmit" id="filesubmit" class="form-control">
                    <p><small>*Submitting new file will replace old file</small></p>
                </div>

                <input type="hidden" name="submissionID" id="submissionID" value="{{ $submission->id }}">
                <div class="d-flex justify-content-center align-items-center">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
