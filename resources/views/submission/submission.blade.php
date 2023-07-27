@extends('layouts.app')
@section('content')
@if(isset($successMessage))
    <!-- display success message -->
    <div class="alert alert-success">{{ $successMessage }}</div>
@endif

@if ($errors->any())
  <!-- display error message -->
    <div class="alert alert-danger">
      Failed to add submission:
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="pagetitle">
    <h1>Submission</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        @if(Auth::user()->usertype != '1')
        <li class="breadcrumb-item"><a href="{{ route('activeclasslistsub') }}">Class</a></li>
        @endif
        <li class="breadcrumb-item active">Submission</li>
    </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
             <div><h5 class="card-title">Submission</h5></div>
             <!-- display add submission button for admin/member -->
             @if(Auth::user()->usertype != '1')
             <div>
                <div data-bs-toggle="modal" data-bs-target="#addSubmissionModal">
                <button class="btn btn-primary">Add Submission</button>
                </div>
            </div>
             @endif
            </div>
            <!-- check if empty -->
            @if(!$submissions->isEmpty())
            <!-- display all submissions -->
            <div class="table-responsive">
                <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Due Date</th>
                        <th>Due Time</th>
                        @if(Auth::user()->usertype != '1')
                        <th>Action</th>
                        @else
                        <th>Status</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($submissions as $index=>$submission)
                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>
                          <!-- different route for different user type -->    
                            @if(Auth::user()->usertype == '1')
                                <a href="{{ route('viewSubmission', ['id' => $submission->id]) }}">{{$submission->submissionName}}</a>
                            @else
                                <a href="{{ route('manageSubmission', ['id' => $submission->id]) }}">{{$submission->submissionName}}</a>
                            @endif
                            @if(Auth::user()->usertype != '1' && $submission->ishidden == true)
                                <i class="bi bi-eye-slash"></i>
                            @endif
                        </td>
                        <td>{{ $submission->formattedDate }}</td>
                        <td>{{ $submission->formattedTime }}</td>
                        <!-- display update/delete button for admin/member -->
                        @if(Auth::user()->usertype != '1')
                        <td>
                            <div class="d-flex">
                            <form action="{{ route('viewSubmissionDetail') }}" method="POST">
                                @csrf
                                <input type="hidden" name="submissionid" id="submissionid" value="{{ $submission->id }}">
                                <button type="submit" class="btn btn-primary me-2">Update</button>
                            </form>
                            <form action="{{ route('deleteSubmissionDetail') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete submission: {{ $submission->submissionName }}?');">
                                @csrf
                                <input type="hidden" name="submissionid" id="submissionid" value="{{ $submission->id }}">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                            </div>
                        </td>
                        @else
                        <!-- check submission status by participant -->
                        @if(isset($submission->status))
                        <td>{{$submission->status}}</td>
                        @else
                        <td>No Submission</td>
                        @endif
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @else
                No Submission Yet
            @endif
          </div>
        </div>
      </div>
    </div>

     <!-- modal add submission -->
     <div class="modal fade" id="addSubmissionModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Add Submission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <div class="modal-body">
                <form method="POST" action="{{ route('createSubmission') }}" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="filetype" id="filetype" value="submission">

                  <div class="form-group mb-3">
                    <label for="filename">Submission Name:</label>
                    <input type="text" class="form-control" id="filename" name="filename" value="{{ old('filename') }}">
                  </div>

                  <div id="submissionform">
                    <div class="form-group mb-3">
                      <label for="submissiondesc">Submission Description:</label>
                      <textarea name="submissiondesc" id="submissiondesc" class="form-control" value="{{ old('submissiondesc') }}" style="display:none"></textarea>
                      <div id="submissiondetail" style="height:100px">

                      </div>
                    </div>
                    <div class="form-group mb-3">
                      <label for="submissiontype">File Type:</label>
                      <select name="submissiontype" id="submissiontype" class="form-select">
                        <option value="allfile">All file</option>
                        <option value="txt">Txt</option>
                        <option value="zip">Zip</option>
                        <option value="pdf">PDF</option>
                        <option value="docx">Docx</option>
                      </select>
                    </div>
                    <div class="form-group mb-3">
                      <label for="submissiondate">Submission Due Date:</label>
                      <input type="date" id="duedate" class="form-control" name="duedate" value="{{ old('duedate') }}">
                    </div>
                    <div class="form-group mb-3">
                      <label for="submissiontime">Submission Due Time:</label>
                      <input type="time" id="duetime" class="form-control" name="duetime" value="{{ old('duetime') }}">
                    </div>
                  </div>

                  <input type="hidden" id="classid" name="classid" value="{{$classroom->id}}">
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add</button>
              </div>
            </form>
          </div>
        </div>
    </div><!-- End add submission Modal-->
  </section>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Include the Quill library -->
  <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
  <script>
    //quill textarea
    $(document).ready(function(){
      var quill2 = new Quill('#submissiondetail', {
      theme: 'snow'
      });
  
      quill2.on('text-change', function(){
        var htmlContent = quill2.root.innerHTML;
        $('#submissiondesc').val(htmlContent);
      });
    });
  </script>
@endsection
