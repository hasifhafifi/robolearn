@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Tournament Report</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('activeclasstournamentreport') }}">Class</a></li>
        @if(isset($user))
        <li class="breadcrumb-item"><a href="{{ route('viewTournamentbyClassParticipant', ['id' => $class->id]) }}">Participant</a></li>
        @else
        <li class="breadcrumb-item"><a href="{{ route('viewTournamentbyClassGroup', ['id' => $class->id]) }}">Group</a></li>
        @endif
        <li class="breadcrumb-item active">Edit Report</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
             <h5 class="card-title">Edit Report</h5>
             <form method="POST" action="{{ route('updateReport') }}" enctype="multipart/form-data">
              @csrf
              @if(isset($user))
              <div class="form-group mb-3">
                <label for="username">Participant:</label>
                <input type="text" class="form-control" id="username" name="username" value="{{$user->username}}" disabled>
              </div>
              @else
              <div class="form-group mb-3">
                <label for="groupname">Group:</label>
                <input type="text" class="form-control" id="groupname" name="groupname" value="{{$group->name}}" disabled>
              </div>
              <div class="form-group mb-3">
                <label for="groupMembers">Members:</label>
              <textarea name="groupMembers" class="form-control" id="groupMembers" rows="3" disabled>@foreach($group->groupMembers as $count=>$member){{$member->username}}, @endforeach</textarea>
              </div>
              @endif
              <div class="form-group mb-3">
                <label for="reportname">Report Title:</label>
                <input type="text" class="form-control" id="reportname" name="reportname" value="{{ $report->name }}">
                @error('reportname')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-group mb-3">
                <label for="tourname">Tournament Title:</label>
                <input type="text" class="form-control" id="tourname" name="tourname" value="{{ $report->tournamentName }}">
                @error('tourname')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-group mb-3">
                <label for="totalmarks">Total Marks:</label>
                <input type="text" class="form-control" id="totalmarks" name="totalmarks" value="{{ $report->totalMarks }}">
                @error('totalmarks')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-group mb-3">
                <label for="checkpoints">Total Checkpoint Passed:</label>
                <input type="text" class="form-control" id="checkpoints" name="checkpoints" value="{{ $report->totalCheckpoints }}">
                @error('checkpoints')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-group mb-3">
                <label for="objective">Achieved Objective:</label><br>
                <input type="radio" class="form-check-input" id="passobjective" name="objective" value="1" {{ $report->passObjective == 1 ? 'checked' : '' }}>
                <label for="passobjective">Pass</label><br>
                <input type="radio" class="form-check-input" id="failobjective" name="objective" value="0" {{ $report->passObjective == 0 ? 'checked' : '' }}>
                <label for="failobjective">Fail</label>
              </div>
              <div class="form-group mb-3">
                <label for="commentdesc">Comment:</label>
                <textarea name="commentdetail" id="commentdetail" class="form-control" value="{{ old('commentdetail') }}" style="display:none">{{ $report->comment }}</textarea>
                <div id="commentdesc" style="height:100px">
                    {!! $report->comment !!}
                </div>
                @error('commentdetail')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>
              @if(isset($user))
              <input type="hidden" name="userID" value="{{$user->id}}">
              @else
              <input type="hidden" name="groupID" value="{{$group->id}}">
              @endif
              <input type="hidden" name="classID" value="{{$class->id}}">
              <input type="hidden" name="reportID" value="{{$report->id}}">
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
      var quill = new Quill('#commentdesc', {
      theme: 'snow'
      });

      quill.on('text-change', function() {
        var editorContent = $(".ql-editor").html();
        if (editorContent.trim() !== '') {
          $('#commentdetail').text(editorContent);
        } else {
          $('#commentdetail').text($('#commentdetail').val());
        }
      });
    });
  </script>
@endsection
