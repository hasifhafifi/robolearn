@extends('layouts.app')

@section('content')
@if(isset($successMessage))
    <div class="alert alert-success">{{ $successMessage }}</div>
@endif

<div class="pagetitle">
    <h1>Tournament Report</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('activeclasstournamentreport') }}">Class</a></li>
        @if(isset($user))
        <li class="breadcrumb-item"><a href="{{ route('viewTournamentbyClassParticipant', ['id' => $class->id]) }}">Participant</a></li>
        @else
        <li class="breadcrumb-item"><a href="{{ route('viewTournamentbyClassGroup', ['id' => $class->id]) }}">Group</a></li>
        @endif
        <li class="breadcrumb-item active">View Report</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="">
                        <h5 class="card-title">View Report</h5>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                      @if(isset($user))
                      <div class="">
                        <a href="{{ route('editReport', ['id' => $report->id]) }}">
                        <button class="btn btn-primary me-2">Edit</button>
                        </a>
                      </div>
                      @else
                        <a href="{{ route('editReportGroup', ['id' => $report->id]) }}">
                        <button class="btn btn-primary me-2">Edit</button>
                        </a>
                      @endif
                      <div class="">
                        <form action="{{ route('deleteReport') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete report: {{ $report->name }}?');">
                          @csrf
                          @if(isset($user))
                          <input type="hidden" name="userID" value="{{ $user->id }}">
                          @else
                          <input type="hidden" name="groupID" value="{{ $group->id }}">
                          @endif
                          <input type="hidden" name="classID" value="{{ $class->id }}">
                          <input type="hidden" name="reportID" value="{{ $report->id }}">
                          <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                      </div>
                    </div>
                </div>
            </div>
             
             <table class="table table-striped table-bordered table-hover table-responsive">
                <tr>
                    @if(isset($user))
                    <th style="width:30%">Participant</th>
                    <td>{{ $user->username }}</td>
                    @else
                    <th style="width:30%">Group</th>
                    <td>{{ $group->name }}</td>
                    @endif
                </tr>
                @if(isset($group))
                <tr>
                  <th>Group Members</th>
                  <td>
                    @foreach($group->groupMembers as $count=>$member)
                    {{$count+1}}. {{$member->username}} <br>
                    @endforeach
                  </td>
                </tr>
                @endif
                <tr>
                    <th>Class</th>
                    <td>{{ $class->className }}</td>
                </tr>
                <tr>
                    <th>Report Name</th>
                    <td>{{ $report->name }}</td>
                </tr>
                <tr>
                    <th>Total Marks</th>
                    <td>{{ $report->totalMarks }}</td>
                </tr>
                <tr>
                    <th>Total Checkpoints Passed</th>
                    <td>{{ $report->totalCheckpoints }}</td>
                </tr>
                <tr>
                    <th>Objective Achieved</th>
                    @if($report->passObjective == true)
                    <td>Passed</td>
                    @else
                    <td>Failed</td>
                    @endif
                </tr>
                <tr>
                    <th>Comment</th>
                    <td>{!! $report->comment !!}</td>
                </tr>
             </table>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
