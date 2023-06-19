@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Manage Participant</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item">Manage Participant</li>
        <li class="breadcrumb-item active">Group</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="d-flex justify-content-between">
                <div class="">
                  <h5 class="card-title">Group</h5>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        @if(!($classrooms->isEmpty()))
                        <button  type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#creategroup">
                            <i class="bi bi-plus-circle"></i><span>&nbspGroup</span>
                         </button>
                         @endif
                    </div>
                    <div>
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
                  </div>
              </div>
            </div>
            
             
             <div class="table-responsive">
            <table class="table table-striped datatable" id="tableall">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Member</th>
                    <th>Created At</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @if(!isset($classView))
                  <tr>
                    <td colspan="100" class="text-center">No Data</td>
                  </tr>
                  @else
                  @foreach($classView->groups as $index=>$group)
                  <tr>
                    <td>{{$index+1}}</td>
                    <td><a href="{{ route('viewgroupdetail', ['id' => $group->id]) }}">{{$group->name}}</a></td>
                    @php
                      $memberArray = json_decode($group->member, true);   
                    @endphp
                    @if (empty($memberArray))
                      <td>0</td>
                    @else
                      <td>{{ count($memberArray) }}</td>
                    @endif
                    <td>{{ $group->created_at }}</td>
                    <td>
                        <form action="{{ route('deleteGroup') }}" method="POST" id="removeForm" onsubmit="return confirm('Are you sure you want to delete this group?');">
                            @csrf
                            <input type="hidden" name="groupID" id="groupID" value="{{ $group->id }}">
                            <button type="submit" class="btn btn-danger">Delete</button>
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

    <!-- modal add group -->
    <div class="modal fade" id="creategroup" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Create New Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <div class="modal-body">
                <form method="POST" action="{{ route('createGroup') }}">
                  @csrf
                  <div class="form-group mb-3">
                    <label for="classname">Group Name:</label>
                    <input type="text" class="form-control" id="classname" name="classname">
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
    </div><!-- End create group Modal-->

  </section>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function () {
        $('#classDropdown').on('change', function () {
            var classID = $(this).val();

            $.ajax({
                type: "GET",
                url: "/home/manageparticipant/class/groupbyclass",
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
                    $.each(data, function (index, group) {
                      var group_id = group.id;
                      var url = "{{ route('viewgroupdetail', ['id' => ':group_id']) }}".replace(':group_id', group_id);
                      var deleteUrl = "{{ route('deleteGroup') }}";
                      var confirmmsg = "Are you sure you want to delete this group?";
                      var memberArray = JSON.parse(group.member);
                      var count = memberArray.length;
                  
                        var row = '<tr>' +
                            '<td>' + (index+1) + '</td>' +
                            '<td><a href="' + url + '">' + group.name +'</a></td>' +
                            '<td>' + count + '</td>' +
                            '<td>' + group.created_at + '</td>' +
                            '<td><form method="POST" action="' + deleteUrl + '" onsubmit="return confirm(\''+confirmmsg+'\');">' + 
                                '@csrf' + 
                                '<input type="hidden" name="groupID" id="groupID" value="' + group.id + '">' +
                                '<button type="submit" class="btn btn-danger">Delete</button>' +
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
