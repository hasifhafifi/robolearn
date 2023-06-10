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
             <h5 class="card-title">Edit File Image</h5>
             <form method="POST" action="{{ route('editFileImage') }}" enctype="multipart/form-data">
              @csrf
              <div class="form-group mb-3">
                <label for="editfilename">File Title:</label>
                <input type="text" class="form-control" id="editfilename" name="editfilename" value="{{ $file->fileName }}" required>
              </div>
              {{-- <div class="form-group mb-3">
                <label for="editfilecontent">File Content:</label>
                <textarea name="editfilecontent" id="editfilecontent" class="form-control" style="display:none">{{ $file->fileContent }}</textarea>
                <div id="editfiledesc" style="height:250px">
                  {!! $file->fileContent !!}
                </div>
              </div> --}}
              <div class="form-group mb-3">
                <label for="isHidden">Visibility:</label><br>
                <input type="checkbox" id="isHidden" name="isHidden" {{ $file->ishidden ? 'checked' : '' }}>Hide File
              </div>

              <div class="d-flex justify-content-center align-items-center mt-2" style="height: 70%;">
                <img src="{{ asset('assets/img/filepics/' . $file->fileContent) }}" alt="Gambar" style="max-width: 50%; height: auto;" class="text-middle">
              </div>

              <div class="form-group mb-3">
                <label for="editfilecontent">Change Image:</label><br>
                <input type="file" id="editfilecontent" name="editfilecontent" class="form-control">
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Include the Quill library -->
  <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
  <!-- Initialize Quill editor -->
  <script>
    $(document).ready(function(){
      var quill = new Quill('#editfiledesc', {
      theme: 'snow'
      });

      quill.on('text-change', function() {
        var editorContent = $(".ql-editor").html();
        if (editorContent.trim() !== '') {
          $('#editfilecontent').text(editorContent);
        } else {
          $('#editfilecontent').text($('#editfilecontent').val());
        }
      });
    });
  </script>
  </section>
@endsection
