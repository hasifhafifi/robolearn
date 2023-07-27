@extends('layouts.app')
@section('content')
<div class="pagetitle">
    <h1>Edit Submission</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('activeclasslist') }}">Class</a></li>
        <li class="breadcrumb-item"><a href="{{ route('viewSubmissionById', ['id' => $submission->classID]) }}">Submissions</a></li>
        <li class="breadcrumb-item active">{{ $submission->submissionName }}</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
             <h5 class="card-title">Edit Submission Detail</h5>
             <!-- form for editing the submission detail -->
             <form method="POST" action="{{ route('editSubmissionDetail') }}" enctype="multipart/form-data">
              @csrf
              <div class="form-group mb-3">
                <label for="editsubname">Submission Name:</label>
                <input type="text" class="form-control @error('editsubname') is-invalid @enderror" id="editsubname" name="editsubname" value="{{ $submission->submissionName }}" required>
                @error('editsubname')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              <div class="form-group mb-3">
                <label for="editsubcontent">Submission Description:</label>
                <textarea name="editsubcontent" id="editsubcontent" class="form-control" style="display:none">{{ $submission->submissionDesc }}</textarea>
                <div id="editsubdesc" style="height:250px">
                  {!! $submission->submissionDesc !!}
                </div>
              </div>
              <div class="form-group mb-3">
                <!-- dropdown for file type of submission -->
                <label for="submissiontype">File Type:</label>
                <select name="submissiontype" id="submissiontype" class="form-select">
                  <option value="allfile" {{ $submission->submissionType== 'allfile' ? 'selected' : '' }}>All file</option>
                  <option value="txt" {{ $submission->submissionType == 'txt' ? 'selected' : '' }}>Txt</option>
                  <option value="zip" {{ $submission->submissionType == 'zip' ? 'selected' : '' }}>Zip</option>
                  <option value="pdf" {{ $submission->submissionType == 'pdf' ? 'selected' : '' }}>PDF</option>
                  <option value="docx" {{ $submission->submissionType == 'docx' ? 'selected' : '' }}>Docx</option>
                </select>
              </div>
              <div class="form-group mb-3">
                <label for="submissiondate">Submission Due Date:</label>
                <input type="date" id="duedate" class="form-control" name="duedate" value="{{ $submission->duedate }}">
              </div>
              <div class="form-group mb-3">
                <label for="submissiontime">Submission Due Time:</label>
                <input type="time" id="duetime" class="form-control" name="duetime" value="{{ $submission->duetime }}">
              </div>
              <div class="form-group mb-3">
                <label for="isHidden">Visibility:</label><br>
                <input type="checkbox" id="isHidden" name="isHidden" {{ $submission->ishidden ? 'checked' : '' }}>Hide File
              </div>
              <input type="hidden" id="editsubid" name="editsubid" value="{{ $submission->id }}">
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
    //quill editor for submission description
    $(document).ready(function(){
      var quill = new Quill('#editsubdesc', {
      theme: 'snow'
      });

      quill.on('text-change', function() {
        var editorContent = $(".ql-editor").html();
        if (editorContent.trim() !== '') {
          $('#editsubcontent').text(editorContent);
        } else {
          $('#editsubcontent').text($('#editsubcontent').val());
        }
      });
    });
  </script>
  </section>
@endsection
