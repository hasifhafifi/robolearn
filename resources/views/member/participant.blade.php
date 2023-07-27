@extends('layouts.app')
@section('content')
<div class="pagetitle">
  <!-- display error message -->
    <h1>Manage Participant</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item">Manage Participant</li>
        <li class="breadcrumb-item active">Participants</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-lg-6">
                  <h5 class="card-title">Participant</h5>
              </div>
              <div class="col-lg-6">
                  <form action="">
                      <div class="row">
                          <div class="col-md-6">
                            
                          </div>
                          <div class="col-md-6 mt-2">
                            <!-- classroom dropdown menu -->
                              <select name="classDropdown" id="classDropdown" class="form-select">
                                  @if($classrooms->isEmpty())
                                  <option value="">None</option>
                                  @else
                                      @foreach($classrooms as $class)
                                          <option value="{{ $class->id }}">{{ $class->className }}</option>
                                      @endforeach
                                  @endif
                              </select>
                          </div>
                      </div>
                  </form>
              </div>
          </div>
            
            <!-- display the list of participants based on the classroom chosen -->
            <div class="table-responsive">
            <table class="table table-striped datatable" id="tableall">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Group</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- check if participants of that class exist -->
                  @if(!isset($classView))
                  <tr>
                    <td colspan="100" class="text-center">No Data</td>
                  </tr>
                  @else
                  @foreach($classView->arrayParticipant as $index => $user)
                  <tr>
                    <td>{{$index+1}}</td>
                    <td><a href="{{ route('viewparticipantdetail', ['id' => $user->id]) }}">{{$user->firstname}} {{$user->lastname}}</a></td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phonenum }}</td>
                    <!-- check if participant is in group -->
                    @if (!isset($user->group))
                      <td>Not in a group</td>
                    @else
                      <td><a href="{{ route('viewgroupdetail', ['id' => $user->group->id]) }}">{{ $user->group->name }}</a></td>
                    @endif
                    <td>
                      <!-- form to remove the participant from the classroom -->
                      <form action="{{ route('removeparticipant') }}" method="POST" id="removeForm" onsubmit="return confirm('Are you sure you want to remove this participant?');">
                        @csrf
                        <input type="hidden" name="userID" id="userID" value="{{ $user->id }}">
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
  </section>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    //dynamically update the classroom list based on chosen classroom from the dropdown menu
    $(document).ready(function () {
        $('#classDropdown').on('change', function () {
            var classID = $(this).val();

            $.ajax({
                type: "GET",
                url: "/home/manageparticipant/class/participantbyclass",
                data: {
                    classid: classID
                },
                success: function (data) {
                    // handle success response
                    var tableBody = $('#tableall tbody');

                    // Clear existing rows
                    tableBody.html('');

                    // Add new rows for each participant
                    $.each(data, function (index, user) {
                      var user_id = user.id;
                      var url = "{{ route('viewparticipantdetail', ['id' => ':user_id']) }}".replace(':user_id', user_id);
                      var deleteUrl = "{{ route('removeparticipant') }}";
                      var confirmmsg = "Are you sure you want to remove this participant?";

                      if(user.hasOwnProperty('group')){
                        var group = user.group.name;
                        var groupID = user.group.id;
                        var urlGroup = "{{ route('viewgroupdetail', ['id' => ':group_id']) }}".replace(':group_id', groupID);
                      }else{
                        var group = "Not set yet"
                        var urlGroup = "{{ route('grouplist') }}";
                      }

                        var row = '<tr>' +
                            '<td>' + (index+1) + '</td>' +
                            '<td><a href="' + url + '">' + user.firstname + ' ' + user.lastname +'</a></td>' +
                            '<td>' + user.username + '</td>' +
                            '<td>' + user.email + '</td>' +
                            '<td>' + user.phonenum + '</td>' +
                            '<td><a href="' + urlGroup + '">' + group + '</a></td>' +
                            '<td><form method="POST" action="' + deleteUrl + '" onsubmit="return confirm(\''+confirmmsg+'\');">' + 
                                '@csrf' + 
                                '<input type="hidden" name="userID" id="userID" value="' + user.id + '">' +
                                '<button type="submit" class="btn btn-danger">Remove</button>' +
                            '</td>'  +
                            '</tr>';

                        tableBody.append(row);
                    });
                },
                error: function (xhr, status, error) {
                    console.log(error);
                }
            });
        });
    });
  </script>

@endsection
