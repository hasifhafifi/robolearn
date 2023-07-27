@extends('layouts.app')

@section('content')
@if(isset($errorMessage))
    <div class="alert alert-danger">{{ $errorMessage }}</div>
@endif

<div class="pagetitle">
    <h1>Edit File</h1>
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
             <h5 class="card-title">Edit File Details</h5>
             <form method="POST" action="{{ route('editFileAllType') }}" enctype="multipart/form-data">
              @csrf
              <!-- name -->
              <div class="form-group mb-3">
                <label for="editfilename">File Title:</label>
                <input type="text" class="form-control" id="editfilename" name="editfilename" value="{{ $file->fileName }}" required>
              </div>
              <!-- visibility -->
              <div class="form-group mb-3">
                <label for="isHidden">Visibility:</label><br>
                <input type="checkbox" id="isHidden" name="isHidden" {{ $file->ishidden ? 'checked' : '' }}>Hide File
              </div>
              <!-- filetype -->
              <div class="form-group mb-3">
                <label>File Type:</label><br>
                {{ $file->fileType }}
              </div>

              <div class="form-group mb-3">
                <label>Uploaded Document:</label><br>
                @if($file->fileType == 'pdf')
                  <a href="{{ route('viewFilePDF', ['id' => $file->id]) }}" target="_blank">{{ $file->fileContent }}</a>
                @elseif($file->fileType == 'zip')
                  <a href="{{ route('viewFileZip', ['id' => $file->id]) }}" target="_blank">{{ $file->fileContent }}</a>
                @elseif($file->fileType == 'docx')
                  <a href="{{ route('viewFileWord', ['id' => $file->id]) }}" target="_blank">{{ $file->fileContent }}</a>
                @endif
              </div>
              <!-- upload file -->
              <div class="form-group mb-3">
                <label for="editfilecontent">New Document:</label><br>
                <input type="file" id="editfilecontent" name="editfilecontent" class="form-control">
                <p><small>*Uploading new file will replace old file</small></p>
              </div>

              <input type="hidden" id="editfileid" name="editfileid" value="{{ $file->id }}">
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Edit</button>
              </div>
             </form>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
