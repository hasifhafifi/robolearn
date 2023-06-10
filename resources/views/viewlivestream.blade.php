@extends('layouts.app')

@section('content')
<div class="pagetitle">
  <h1>Livestream</h1>
  <nav>
  <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('livestream') }}">List of Livestream</a></li>
      <li class="breadcrumb-item active">Livestream</li>
  </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">{{ $livestream->streamName }}</h5>
            <p>{{ $livestream->streamDesc }}</p>
            <div style="display: flex; justify-content: center;">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/{{ $livestream->yt_streamID }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
            {{-- <iframe width="350px" height="500px" src="https://www.youtube.com/live_chat?v=jfKfPfyJRdk&amp;embed_domain=http://127.0.0.1:8000" ></iframe> --}}
          </div>
        </div>
      </div>
    </div>
</section>
@endsection