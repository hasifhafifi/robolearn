@extends('layouts.app')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
      Failed to create new class:
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="pagetitle">
    <h1>Manage Participant</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item">Manage Participant</li>
        <li class="breadcrumb-item active">Class</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Classroom</h5>
            <div class="position-absolute top-0 end-0 p-3">
            <button  type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#verticalycentered">
               <i class="bi bi-plus-circle"></i><span>&nbspCreate Class</span>
            </button>
            </div>
            <div class="table-responsive">
            <table class="table table-striped datatable" id="tableall">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Class Name</th>
                  <th>Class Code</th>
                  <th>Total Participant</th>
                  <th>Created At</th>
                  <th>Registration</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($classrooms as $index => $classroom)
                <tr>
                  <td>{{$index+1}}</td>
                  <td>{{$classroom->className}}</td>
                  <td>{{ $classroom->classCode }}</td>
                  @if(empty($classroom->classParticipant))
                    <td>0</td>
                  @else
                    <td>{{ count(json_decode($classroom->classParticipant, true)) }}</td>
                  @endif
                  <td>{{ $classroom->created_at->format('d F Y, H:i A') }}</td>
                  <td>
                    <form action="{{ route('updateRegistration')}}" method="POST" id="updateRegistration">
                      @csrf
                      <div class="form-check form-switch">
                        @if($classroom->isFull == 0)
                          <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked onchange="checkFormSwitch('{{ $classroom->id }}', '1')">
                        @else
                          <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" onchange="checkFormSwitch('{{ $classroom->id }}', '0')">
                        @endif
                        </div>
                        <input type="hidden" name="isFull" id="isFull" value="">
                        <input type="hidden" name="classID" id="classID" value="">
                    </form>
                  </td>
                  @if ($classroom->isAvailable == 1)
                    <td><button  type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#classavailable" onclick="passID('{{ $classroom->id }}')">Active</button></td>
                  @else
                    <td><button  type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#classavailable" onclick="passID('{{ $classroom->id }}')">Inactive</button></td> 
                  @endif
                  
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          </div>
        </div>
      </div>
    </div>

    <!-- modal add class -->
    <div class="modal fade" id="verticalycentered" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Create New Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <div class="modal-body">
                <form method="POST" action="{{ route('createClassroom') }}">
                  @csrf
                  <div class="form-group mb-3">
                    <label for="classname">Class Name:</label>
                    <input type="text" class="form-control" id="classname" name="classname">
                  </div>
                  
                  <label for="classcode">Class Code:</label>
                  <div class="input-group mb-3">
                    <input type="text" class="form-control" id="classcode" name="classcode">
                    <div class="input-group-append">
                      <button type="button" class="btn btn-primary" onclick="generateCode()"><i class="bi bi-arrow-counterclockwise"></i></button>
                    </div>
                  </div>
                
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Create</button>
              </div>
            </form>
          </div>
        </div>
    </div><!-- End Vertically centered Modal-->

     <!-- modal change class availability -->
     <div class="modal fade" id="classavailable" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Change Class Availability</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
              <form method="POST" action="{{ route('changeClassroomAvailability') }}">
                @csrf
                <div class="form-group mb-3">
                  <label for="isAvailable">Class Availability:</label>
                  <select name="isAvailable" id="isAvailable" class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                  </select>
                  <input type="hidden" id="classroom_id" name="classroom_id" value="">
                </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div><!-- End Vertically centered Modal-->
  </section>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    function generateCode() {
        // Make an API call to random.org to get a random number
        $.ajax({
          url: "https://api.random.org/json-rpc/2/invoke",
          type: "POST",
          contentType: "application/json",
          data: JSON.stringify({
            "jsonrpc": "2.0",
            "method": "generateStrings",
            "params": {
              "apiKey": "5fb6924b-ecd8-416f-84d1-bc72d292b23e",
              "n": 1,
              "length": 6,
              "characters": "abcdefghijklmnopqrstuvwxyz0123456789",
              "replacement": true
            },
            "id": 1
          }),
          success: function(response) {
            $("#classcode").val(response.result.random.data[0]);
          },
          error: function(error) {
            console.log(xhr.responseText);
          }
        });
    }
    </script>
    <script>
        function passID(id){
          $('#classroom_id').val(id);
        }

        function checkFormSwitch(classID, value){
          $('#isFull').val(value);
          $('#classID').val(classID);

          const form = document.querySelector('#updateRegistration');
          form.submit();
        }
    </script>
  
@endsection
