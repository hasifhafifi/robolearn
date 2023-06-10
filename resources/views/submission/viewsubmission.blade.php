@extends('layouts.app')

@section('content')
@if(isset($successMessage))
    <div class="alert alert-success">{{ $successMessage }}</div>
@endif

<div class="pagetitle">
    <h1>Submission</h1>
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
             <h5 class="card-title">Submission</h5>
             <table class="table table-striped table-bordered table-hover table-responsive">
                <tr>
                    <th style="width:20%">Submission Name</th>
                    <td>{{ $submission->submissionName }}</td>
                </tr>
                <tr>
                    <th>Submission Description</th>
                    <td>{!! $submission->submissionDesc !!}</td>
                </tr>
                <tr>
                    <th>Submission Type</th>
                    <td>
                        @if($submission->submissionType == 'allfile')
                        All File
                        @else
                        {{ $submission->submissionType }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Submission Due</th>
                    @if($submission->timeSign == 'negative')
                    <td class="table-danger">
                        <strong>Date and Time:</strong> {{ $submission->formattedTime }}, {{ $submission->formattedDate }}
                        <br>
                        <strong>Time Left:</strong> -{{ $submission->timeLeft }}
                    </td>
                    @else
                    <td>
                        <strong>Date and Time:</strong> {{ $submission->formattedTime }}, {{ $submission->formattedDate }}
                        <br>
                        <strong>Time Left:</strong> {{ $submission->timeLeft }}
                    </td>
                    @endif
                </tr>
                <tr>
                    <th>Submission</th>
                    
                        @if(isset($submissionFile))
                        <td class="table-success">
                        Submitted
                        <br>
                        <a href="{{ route('getSubmission', ['id' => $submissionFile->id]) }}">{{ $submissionFile->submittedFileName }}</a>
                        </td>
                        @else
                        <td>
                        No submission yet
                        </td>
                        @endif
                </tr>
             </table>
             <div class="d-flex justify-content-center align-items-center">
              @if(!isset($submissionFile))
                <form action="{{ route('addSubmission') }}" method="POST">
                    @csrf
                    <input type="hidden" name="submissionID" id="submissionID" value="{{$submission->id}}">
                    <button class="btn btn-primary">Add Submission</button>
                </form>
              @else
              <div class="p-2">
                <form action="{{ route('editSubmission') }}" method="POST">
                  @csrf
                  <input type="hidden" name="submissionID" id="submissionID" value="{{$submission->id}}">
                  <button class="btn btn-primary">Edit Submission</button>
                </form>
              </div>
                <div class="">
                  <form action="{{ route('removeSubmission') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete submission: {{ $submissionFile->submittedFileName }}?');">
                  @csrf
                  <input type="hidden" name="submissionID" id="submissionID" value="{{$submission->id}}">
                  <input type="hidden" name="submittedFileID" id="submittedFileID" value="{{$submissionFile->id}}">
                  <button class="btn btn-danger">Delete Submission</button>
                </form>
                </div>
                
              @endif
             </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
