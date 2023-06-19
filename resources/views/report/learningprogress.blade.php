@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Participant Progress</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active"><a href="{{ route('activeclassprogressreport') }}">Class</a></li>
        <li class="breadcrumb-item active">{{$class->className}}</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">{{$class->className}}</h5>
            <div class="table-responsive">
            <table class="table table-striped datatable" id="tableall">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Username</th>
                  @foreach($modules as $module)
                  <th>{{$module->moduleName}}</th>
                  @endforeach
                </tr>
              </thead>
              <tbody>
                @foreach ($participants as $index => $participant)
                <tr>
                  <td>{{$index+1}}</td>
                  <td><a href="{{ route('viewParticipantModule', ['id' => $participant->id]) }}">{{$participant->username}}</a></td>
                  @foreach($participant->arrPercentage as $arr)
                    <td>{{$arr['percentage']}}%</td>
                  @endforeach
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
