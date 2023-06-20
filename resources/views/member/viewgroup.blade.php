@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Manage Participant</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item">Manage Participant</li>
        <li class="breadcrumb-item">Group</li>
        <li class="breadcrumb-item active">Group {{ $group->name }}</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
             <h5 class="card-title">Group {{ $group->name }}</h5>
             <div class="position-absolute top-0 end-0 p-3">
                <button  type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addmember">
                   <i class="bi bi-plus-circle"></i><span>&nbspAdd Member</span>
                </button>
            </div>
             <div class="table-responsive">
            <table class="table table-striped datatable" id="tableall">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @if(empty($group->users))
                  <tr>
                    <td colspan="100" class="text-center">No Data</td>
                  </tr>
                  @else
                  @foreach($group->users as $index => $user)
                  <tr>
                    <td>{{$index+1}}</td>
                    <td><a href="{{ route('viewparticipantdetail', ['id' => $user->id]) }}">{{$user->firstname}} {{$user->lastname}}</a></td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phonenum }}</td>
                    <td>
                      <form action="{{ route('removeparticipantfromgroup') }}" method="POST" id="removeForm" onsubmit="return confirm('Are you sure you want to remove this participant from this group?');">
                        @csrf
                        <input type="hidden" name="userID" id="userID" value="{{ $user->id }}">
                        <input type="hidden" name="groupID" id="groupID" value="{{ $group->id }}">
                        <button type="submit" class="btn btn-danger">Remove</button>
                      </form>
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
    </div>

     <!-- modal add member -->
     <div class="modal fade" id="addmember" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Assign participant to Group {{$group->name}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <div class="modal-body">
                <form method="POST" action="{{ route('assignParticipantToGroup') }}">
                  @csrf
                  <div class="form-group mb-3">
                    <label for="participant">Participant Name:</label>
                    <select name="participant" id="participant" class="form-select">
                        @if(empty($participantArray))
                        <option value="">No Participant</option>
                        @else
                        <option disabled selected value>-- Choose Participant --</option>
                        @foreach($participantArray as $participant)
                        <option value="{{ $participant->id }}">{{ $participant->firstname }} {{ $participant->lastname }}</option>
                        @endforeach
                        @endif
                    </select>
                  </div>
                  {{-- <div class="form-group mb-3" id="dynamic_field"></div>
                  <div class="form-group mb-3">
                    <button type="button" class="btn btn-primary" name="add" id="add"><i class="bi bi-plus"></i> </button>
                    <button type="button" class="btn btn-danger" name="remove" id="remove"><i class="bi bi-dash"></i> </button>
                  </div> --}}
              </div>
              <input type="hidden" id="groupID" name="groupID" value="{{ $group->id }}">
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add</button>
              </div>
            </form>
          </div>
        </div>
    </div><!-- End addmember Modal-->

  </section>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function(){
    var i=1;
    $('#add').click(function(){
    i++;
    $('#dynamic_field').append('<select name="participant" id="participant" class="form-select"><option>satu</option></select>');
    });
      
    $(document).on('click', '#remove', function(){
    var button_id = $(this).attr("id"); 
    $('#row'+button_id+'').remove();
    });
    });
    </script>


@endsection
