@extends('layouts.app')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
      Failed to create new livestream:
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


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
            <div class="position-absolute top-0 end-0 p-3">
              <button  type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#livestreammodal">
                 <i class="bi bi-plus-circle"></i><span>&nbspAdd Livestream</span>
              </button>
            </div>
            <div class="table-responsive">
              <table class="table table-striped datatable" id="tableall">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Class Name</th>
                    <th>Youtube ID</th>
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
                      <td>{{ $livestream->classname }}</td>
                      <td>{{ $livestream->yt_streamID }}</td>
                      <td>{{ $livestream->formattedDate }}</td>
                      <td>{{ $livestream->formattedTime }}</td>
                      <td>
                        <div class="d-flex">
                          <div type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#livestreameditmodal" onclick="getLivestream('{{ $livestream->id }}')">Edit</div>
                          <form action="{{ route('deleteLivestream', $livestream->id) }}" method="POST">
                              @csrf
                              <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete the livestream: {{ $livestream->streamName }}?')">Delete</button>
                          </form>
                      </div>
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

    <!-- modal add livestream -->
    <div class="modal fade" id="livestreammodal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Add New Livestream</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
              <form method="POST" action="{{ route('addlivestream') }}">
                @csrf
                <div class="form-group mb-3">
                  <label for="classname">Livestream Name:</label>
                  <input type="text" class="form-control" id="streamname" name="streamname" value="{{ old('streamname') }}">
                </div>

                <div class="form-group mb-3">
                  <label for="classname">Livestream Description:</label>
                  <input type="text" class="form-control" id="streamdesc" name="streamdesc" value="{{ old('streamdesc') }}">
                </div>

                <div class="form-group mb-3">
                  <label for="classname">Livestream Date:</label>
                  <input type="date" class="form-control" id="streamdate" name="streamdate" value="{{ old('streamdate') }}">
                </div>

                <div class="form-group mb-3">
                  <label for="classname">Livestream Time:</label>
                  <input type="time" class="form-control" id="streamtime" name="streamtime" value="{{ old('streamtime') }}">
                </div>
                
                <div class="form-group mb-3">
                  <label for="classname">Youtube Livestream Link:</label>
                  <input type="text" class="form-control" id="ytstreamid" name="ytstreamid" value="{{ old('ytstreamid') }}">
                </div>

                <label for="classSelect">Class:</label>
                  <select name="classSelect" id="classSelect" class="form-select">
                    @foreach($classrooms as $class)
                        <option value="{{ $class->id }}">{{ $class->className }}</option>
                    @endforeach
                </select>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Create</button>
            </div>
          </form>
        </div>
      </div>
  </div><!-- End add livestream Modal-->

   <!-- modal edit livestream -->
   <div class="editModal">
   <div class="modal fade" id="livestreameditmodal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Livestream</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <form method="POST" action="{{ route('editLivestream') }}">
              @csrf
              <input type="hidden" class="form-control" id="livestreamID" name="livestreamID" value="">
              <div class="form-group mb-3">
                <label for="classname">Livestream Name:</label>
                <input type="text" class="form-control" id="editstreamname" name="editstreamname">
              </div>

              <div class="form-group mb-3">
                <label for="classname">Livestream Description:</label>
                <input type="text" class="form-control" id="editstreamdesc" name="editstreamdesc">
              </div>

              <div class="form-group mb-3">
                <label for="classname">Livestream Date:</label>
                <input type="date" class="form-control" id="editstreamdate" name="editstreamdate">
              </div>

              <div class="form-group mb-3">
                <label for="classname">Livestream Time:</label>
                <input type="time" class="form-control" id="editstreamtime" name="editstreamtime">
              </div>
              
              <div class="form-group mb-3">
                <label for="classname">Youtube Livestream Link:</label>
                <input type="text" class="form-control" id="editytstreamid" name="editytstreamid">
              </div>

              <label for="classSelect">Class:</label>
                <select name="editclassSelect" id="editclassSelect" class="form-select">
                  @foreach($classrooms as $class)
                      <option value="{{ $class->id }}">{{ $class->className }}</option>
                  @endforeach
              </select>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Edit</button>
          </div>
        </form>
      </div>
    </div>
</div>
</div><!-- End edit livestream Modal-->
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function getLivestream(id) {
  $.ajax({
    url: '/livestream/edit/' + id,
    method: 'GET',
    success: function(response) {
      // Handle the response here
      console.log(response);
      // Update the modal with the retrieved livestream data
      $('#livestreamID').val(response.id);
      $('#editstreamname').val(response.streamName);
      $('#editstreamdesc').val(response.streamDesc);
      $('#editstreamdate').val(response.streamDate);
      $('#editstreamtime').val(response.streamTime);
      $('#editytstreamid').val("https://www.youtube.com/watch?v=" + response.yt_streamID);
      
      // Get the selected class ID
      var selectedClassId = response.classroomID;
          
      // Set the selected option in the class select element
      $('#editclassSelect').val(selectedClassId);
    },
    error: function(xhr, status, error) {
      // Handle the error here
      console.log(error);
    }
  });
}

</script>
@endsection