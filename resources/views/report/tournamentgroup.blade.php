@extends('layouts.app')
@section('content')
<div class="pagetitle">
    <h1>Tournament Report</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('activeclasstournamentreport') }}">Class</a></li>
        <li class="breadcrumb-item active">Group</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
             <h5 class="card-title">Group</h5>
             <!-- list all the groups in that class -->
            <div class="table-responsive">
            <table class="table table-striped datatable" id="tableall">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Group Name</th>
                    <th>Group Members</th>
                    <th>Report</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- check if group exist -->
                  @if(!isset($groups))
                  <tr>
                    <td colspan="100" class="text-center">No Data</td>
                  </tr>
                  @else
                  <!-- loop all the groups -->
                  @foreach($groups as $index => $group)
                  <tr>
                    <td>{{$index+1}}</td>
                    <td>{{ $group->name }}</td>
                    <td>
                        @if(!($group->groupMembers->isEmpty()))
                        <!-- list all the group members -->
                        @foreach($group->groupMembers as $count=>$member)
                        {{ $count+1 }}. {{ $member->username }} <br>
                        @endforeach
                        @else
                        No Data
                        @endif
                    </td>
                    <!-- check if report already existed -->
                    @if(isset($group->reportID))
                    <td>
                      <a href="{{ route('viewReportGroup', ['id' => $group->reportID]) }}">
                            <button class="btn btn-success">
                                View Report
                            </button>
                        </a>
                    </td>
                    @else
                    <!-- if doesnt exist, display add report button -->
                    <td>
                      <a href="{{ route('reportFormGroup', ['id' => $group->id]) }}">
                            <button type="submit" class="btn btn-primary">
                                Add Report
                            </button>
                        </a>
                    </td>
                    @endif
                  </tr>
                  @endforeach
                  @endif
                </tbody>
              </table>
            </div> 
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
