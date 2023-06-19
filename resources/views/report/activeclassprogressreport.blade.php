@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Participant Progress</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Class</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Active Classrooms</h5>
            <div class="table-responsive">
            <table class="table table-striped datatable" id="tableall">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Class Name</th>
                  <th>Class Code</th>
                  <th>Total Participant</th>
                  <th>Total Module</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($classrooms as $index => $classroom)
                <tr>
                  <td>{{$index+1}}</td>
                  <td><a href="{{ route('viewProgressbyClass', ['id' => $classroom->id]) }}">{{$classroom->className}}</a></td>
                  <td>{{ $classroom->classCode }}</td>
                  @if(empty($classroom->classParticipant))
                    <td>0</td>
                  @else
                    <td>{{ count(json_decode($classroom->classParticipant, true)) }}</td>
                  @endif
                  <td>{{ $classroom->modulesCount }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  
@endsection
