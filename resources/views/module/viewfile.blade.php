@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>View File</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('activeclasslist') }}">Class</a></li>
        <li class="breadcrumb-item"><a href="{{ route('viewModuleById', ['id' => $module->classroomID]) }}">Module</a></li>
        <li class="breadcrumb-item"><a href="{{ route('viewmodule', ['id' => $module->id]) }}">{{ $module->moduleName }}</a></li>
        <li class="breadcrumb-item active">{{ $document->docTitle }}</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
             <h5 class="card-title">{{ $file->fileName }}</h5>
             <!-- display text content -->
             @if($file->fileType == 'text')
                {!! $file->fileContent !!}
              <!-- display image -->  
             @elseif($file->fileType == 'image')
                <img src="{{ asset('assets/img/filepics/' . $file->fileContent) }}" alt="Gambar" style="max-width: 100%; height: auto;">
              <!-- display url -->
             @elseif($file->fileType == 'url')
                Click <a href="https://{{ $file->fileContent }}" target="_blank">{{$file->fileContent}}</a> to continue
              <!-- display youtube video --> 
             @elseif($file->fileType == 'yturl')
                <div style="display: flex; justify-content: center;">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/{{$file->fileContent}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
             @endif
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
