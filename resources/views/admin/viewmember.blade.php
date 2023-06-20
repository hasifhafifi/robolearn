@extends ('layouts.app')
@section('content')
    <div class="pagetitle">
      <h1>Robotic Club Members</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active">Manage Member</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section>
        <div class="row">
    
          <div class="col-lg-12">
    
            <div class="card">
              <div class="card-body pt-3">
                <!-- Bordered Tabs -->
                <ul class="nav nav-tabs nav-tabs-bordered">
    
                  <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#all-members">All Members</button>
                  </li>
    
                  <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#new-members">New Members</button>
                  </li>
    
                </ul>
                <div class="tab-content pt-2">
    
                  <div class="tab-pane fade show active all-members" id="all-members">
                    <table class="table table-striped" id="tableall">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Matric Number</th>
                            <th>Phone Number</th>
                            <th>Verification Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($listmembers as $index => $listmember)
                          <tr>
                            <td>{{$index+1}}</td>
                            <td>{{$listmember->username}}</td>
                            <td><a href="{{ route('viewmemberdetail', ['id' => $listmember->id]) }}">{{$listmember->firstname}} {{$listmember->lastname}}</a></td>
                            <td>{{$listmember->email}}</td>
                            <td>{{ $listmember->members->matricNum }}</td>
                            <td>{{ $listmember->phonenum }}</td>
                            <td>
                                @if ($listmember->members->verified == '1')
                                <button class="btn btn-success" disabled>Verified</button>
                                @else
                                <button class="btn btn-warning" disabled>Not Verified</button>
                                @endif
                            </td>
                            <td>
                              <form action="{{ route('removemember', ['id' => $listmember->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to remove: {{ $listmember->username }}?');">
                                @csrf
                                <button type="submit" class="btn btn-danger">Remove</button>
                              </form>
                            </td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                  </div>
    
                  <div class="tab-pane fade new-members pt-3" id="new-members">
    
                    <table class="table table-striped" id="tablenew">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Matric Num</th>
                            <th>Verification Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($newmembers as $index => $newmember)
                          <tr>
                            <td>{{$index+1}}</td>
                            <td><a href="{{ route('viewmemberdetail', ['id' => $newmember->id]) }}">{{$newmember->firstname}} {{$newmember->lastname}}</a></td>
                            <td>{{$newmember->email}}</td>
                            <td>{{ $newmember->matricNum }}</td>
                            <td>
                                @if ($newmember->verified == '1')
                                <button class="btn btn-success" disabled>Verified</button>
                                @else
                                <button class="btn btn-warning" disabled>Not Verified</button>
                                @endif
                            </td>
                            <td>
                              <form action="{{ route('verifymember', ['id' => $newmember->id]) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary">Verify</button>
                              </form>
                              <form action="{{ route('removemember', ['id' => $newmember->id]) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger">Remove</button>
                              </form>
                            </td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
    
                  </div>
    
                </div><!-- End Bordered Tabs -->
    
              </div>
            </div>
    
          </div>
        </div>
      </section>
      <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
      <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
      <script>
        $(document).ready(function() {
            $('#tableall').DataTable();
            $('#tablenew').DataTable();
        });
      </script>

@endsection