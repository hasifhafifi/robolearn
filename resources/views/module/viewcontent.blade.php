@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Edit Content</h1>
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
             <h5 class="card-title">Edit Content</h5>
             <form method="POST" action="{{ route('editContent') }}" enctype="multipart/form-data">
              @csrf
              <!-- name -->
              <div class="form-group mb-3">
                <label for="editcontentname">Content Title:</label>
                <input type="text" class="form-control @error('editcontentname') is-invalid @enderror" id="editcontentname" name="editcontentname" value="{{ $document->docTitle }}">
                @error('editcontentname')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              <!-- description -->
              <div class="form-group mb-3">
                <label for="editcontentdetail">Content Description:</label>
                <textarea name="editcontentdetail" id="editcontentdetail" class="form-control @error('editcontentdetail') is-invalid @enderror" style="display:none">{{ $document->docDesc }}</textarea>
                <div id="editcontentdesc" style="height:250px">
                  {!! $document->docDesc !!}
                </div>
                @error('editcontentdetail')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              <!-- visibility -->
              <div class="form-group mb-3">
                <label for="isHidden">Visibility:</label><br>
                <input type="checkbox" id="isHidden" name="isHidden" {{ $document->isHidden ? 'checked' : '' }}>Hide Section
              </div>
              <input type="hidden" id="editcontentid" name="editcontentid" value="{{ $document->id }}">
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
      var quill = new Quill('#editcontentdesc', {
      theme: 'snow'
      });

      quill.on('text-change', function() {
        var editorContent = $(".ql-editor").html();
        if (editorContent.trim() !== '') {
          $('#editcontentdetail').text(editorContent);
        } else {
          $('#editcontentdetail').text($('#editcontentdetail').val());
        }
      });
    });
  </script>
@endsection
