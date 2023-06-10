@extends('layouts.app')

@section('content')
<div class="pagetitle">
  <h1>Livestream</h1>
  <nav>
  <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
      <li class="breadcrumb-item active">Livestream</li>
  </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Livestream</h5>
            <div class="table-responsive">
              <table class="table table-striped datatable" id="tableall">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @if($livestreams->isEmpty())
                    <td colspan="6" class="text-center align-middle">No livestream</td>
                  @else
                    @foreach($livestreams as $index=>$livestream)
                    <tr>
                      <td>{{$index+1}}</td>
                      <td><a href="{{ route('viewlivestream', ['id' => $livestream->id]) }}">{{ $livestream->streamName }}</a></td>
                      <td>{{ $livestream->streamDesc }}</td>
                      <td>{{ $livestream->formattedDate }}</td>
                      <td>{{ $livestream->formattedTime }}</td>
                      <td>
                        <a href="{{ route('viewlivestream', ['id' => $livestream->id]) }}" class="btn btn-primary">Watch</a>
                      </td>
                    </tr>
                    @endforeach
                  @endif
                </tbody>
              </table>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection