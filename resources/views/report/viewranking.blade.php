@extends('layouts.app')

@section('content')
@if(isset($successMessage))
    <div class="alert alert-success">{{ $successMessage }}</div>
@endif

<div class="pagetitle">
    <h1>Tournament Ranking</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('activeclasstournamentreport') }}">Class</a></li>
        <li class="breadcrumb-item active">View Ranking</li>
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
                        <h5 class="card-title">View Ranking</h5>
                    </div>
                </div>
            </div>
             
             <table class="table table-striped table-bordered table-hover table-responsive">
                <thead>
                    <th>No</th>
                    <th>Group/User Name</th>
                    <th>Total Marks</th>
                    <th>Total Checkpoints Passed</th>
                    <th>Objective</th>
                </thead>
                <tbody>
                    @if(!$reports->isEmpty())
                    @foreach($reports as $index=>$report)
                    <tr>
                        <td>{{ $index+1 }}</td>
                        @if(isset($report->user))
                        <td>{{ $report->user->username }}</td>
                        @else
                        <td>{{ $report->group->name }}</td>
                        @endif
                        <td>{{ $report->totalMarks }}</td>
                        <td>{{ $report->totalCheckpoints }}</td>
                        @if($report->passObjective == true)
                        <td>Passed</td>
                        @else
                        <td>Failed</td>
                        @endif
                    </tr>
                    @endforeach
                    @else
                    <tr><td colspan="5" class="text-center">No Data</td></tr>
                    @endif
                </tbody>
             </table>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
