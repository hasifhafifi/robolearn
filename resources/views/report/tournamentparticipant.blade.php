@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Tournament Report</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('activeclasstournamentreport') }}">Class</a></li>
        <li class="breadcrumb-item active">Participant</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
             <h5 class="card-title">Participant</h5>
            
             <div class="table-responsive">
            <table class="table table-striped datatable" id="tableall">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Group</th>
                    <th>Report</th>
                  </tr>
                </thead>
                <tbody>
                  @if(!isset($users))
                  <tr>
                    <td colspan="100" class="text-center">No Data</td>
                  </tr>
                  @else
                  @foreach($users as $index => $user)
                  <tr>
                    <td>{{$index+1}}</td>
                    <td><a href="{{ route('viewparticipantdetail', ['id' => $user->id]) }}">{{$user->firstname}} {{$user->lastname}}</a></td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phonenum }}</td>
                    <td>{{ $user->groupName }}</td>
                    @if(isset($user->reportID))
                    <td>
                        <a href="{{ route('viewReport', ['id' => $user->reportID]) }}">
                            <button class="btn btn-success">
                                View Report
                            </button>
                        </a>
                    </td>
                    @else
                    <td>
                        <a href="{{ route('reportForm', ['id' => $user->id]) }}">
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
