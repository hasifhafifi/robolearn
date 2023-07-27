@extends('layouts.app')

@section('content')
@if(isset($errorMessage))
    <div class="alert alert-danger">{{ $errorMessage }}</div>
@endif

<div class="pagetitle">
    <h1>View File</h1>
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
            <h5 class="card-title">Add Submission</h5>
            <div class="p-3 mb-2 bg-light rounded">
                Submission Description: {!! $submission->submissionDesc !!}
                <br>
                Submission Type:
                @if($submission->submissionType == 'allfile')
                All File
                @else
                {{ $submission->submissionType }}
                @endif
            </div>
            <form action="{{ route('submitFile') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="filesubmit">Add File:</label>
                    <input type="file" name="filesubmit" id="filesubmit" class="form-control">
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
