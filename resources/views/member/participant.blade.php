@extends('layouts.app')

@section('content')
<div class="pagetitle">
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
             <h5 class="card-title">Participant</h5>
             <div class="position-absolute top-0 end-0 p-3">
                      <form action="">
                        <select name="classDropdown" id="classDropdown" class="form-select">
                            @if($classrooms->isEmpty())
                            <option value="">None</option>
                            @else
                                @foreach($classrooms as $class)
                                    <option value="{{ $class->id }}">{{ $class->className }}</option>
                                @endforeach
                            @endif
                        </select>
                    </form>
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
                    <th>Group</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
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
                    @if (!isset($user->group))
                      <td><a href="{{ route('group') }}">Not set yet</a></td>
                    @else
                      <td><a href="{{ route('viewgroupdetail', ['id' => $user->group->id]) }}">{{ $user->group->name }}</a></td>
                    @endif
                    <td>
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
                    // console.log(data);
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
                        var urlGroup = "{{ route('group') }}";
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
                    // handle error response
                }
            });
        });
    });
  </script>

@endsection
