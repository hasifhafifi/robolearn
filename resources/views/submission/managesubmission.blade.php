@extends('layouts.app')

@section('content')
@if(isset($successMessage))
    <div class="alert alert-success">{{ $successMessage }}</div>
@endif

<div class="pagetitle">
    <h1>View Submission</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('activeclasslistsub') }}">Class</a></li>
        <li class="breadcrumb-item"><a href="{{ route('viewSubmissionById', ['id' => $submission->classID]) }}">Submissions</a></li>
        <li class="breadcrumb-item active">{{ $submission->submissionName }}</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
             <h5 class="card-title">Manage Submission</h5>
             <h6><b>Submission Detail:</b></h6>
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
                        <strong>Date and Time:</strong> {{ $submission->formattedDate }} {{ $submission->formattedTime }}
                        <br>
                        <strong>Time Left:</strong> -{{ $submission->timeLeft }}
                    </td>
                    @else
                    <td>
                        <strong>Date and Time:</strong> {{ $submission->formattedDate }} {{ $submission->formattedTime }}
                        <br>
                        <strong>Time Left:</strong> {{ $submission->timeLeft }}
                    </td>
                    @endif
                </tr>
             </table>
             <div class="table-responsive">
             <table class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Group</th>
                        <th>Submission Status</th>
                        <th>Submitted File</th>
                        <th>Submission Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($participants as $index=>$participant)
                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $participant->userdetail->username }}</td>
                        <td>
                            @if(isset($participant->groupName))
                            {{ $participant->groupName }}
                            @else
                            None
                            @endif
                        </td>
                        <td>
                            @if(isset($participant->file))
                            Submitted
                            @else
                            No submission yet
                            @endif
                        </td>
                        <td>
                            @if(isset($participant->file))
                            {{ $participant->file->submittedFileName }}
                            @else
                            No submission yet
                            @endif
                        </td>
                        <td>
                            @if(isset($participant->file))
                            {{ $participant->file->formattedDateTime }}
                            @else
                            No submission yet
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
             </table>
             <div class="d-flex justify-content-center align-items-center">
                <form action="{{ route('getAllSubmission') }}" method="POST">
                    @csrf
                    <input type="hidden" name="submissionID" id="submissionID" value="{{$submission->id}}">
                    <button class="btn btn-primary">Download All</button>
                </form>
             </div>
             </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
