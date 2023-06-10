@extends('layouts.app')

@section('content')
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
             <h5 class="card-title">Edit File URL</h5>
             <form method="POST" action="{{ route('editFileURL') }}" enctype="multipart/form-data">
              @csrf
              <div class="form-group mb-3">
                <label for="editfilename">File Title:</label>
                <input type="text" class="form-control" id="editfilename" name="editfilename" value="{{ $file->fileName }}" required>
              </div>

              <div class="form-group mb-3">
                <label for="isHidden">Visibility:</label><br>
                <input type="checkbox" id="isHidden" name="isHidden" {{ $file->ishidden ? 'checked' : '' }}>Hide File
              </div>

              @if($file->fileType == 'url')
              <div class="form-group mb-3">
                <label for="editfilecontent">URL Link:</label><br>
                <input type="text" id="editfilecontent" name="editfilecontent" class="form-control" value="{{ $file->fileContent }}" required>
              </div>
              @else
              <div class="form-group mb-3">
                <label for="editfilecontent">Youtube Video Link:</label><br>
                <input type="text" id="editfilecontent" name="editfilecontent" class="form-control" value="https://www.youtube.com/watch?v={{ $file->fileContent }}" required>
              </div>
              @endif

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
