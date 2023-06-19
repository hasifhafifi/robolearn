@extends ('layouts.app')
@section('content')
<div class="pagetitle">
  <h1>Profile</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
      <li class="breadcrumb-item active">Profile</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section profile">
    <div class="row">
      <div class="col-xl-4">

        <div class="card">
          <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

            <img src="{{ asset('assets/img/profilepics/' . $userDetails->profilepic) }}" alt="Profile" class="rounded-circle">
            <h2>{{ $userDetails->username }}</h2>
            
            @if($userDetails->usertype == 1)
              <h3>Participant</h3>
            @elseif($userDetails->usertype == 2)
              <h3>Club Member</h3>
            @elseif($userDetails->usertype == 3)
              <h3>Admin</h3>
            @endif
          </div>
        </div>

      </div>

      <div class="col-xl-8">

        <div class="card">
          <div class="card-body pt-3">
            <!-- Bordered Tabs -->
            <ul class="nav nav-tabs nav-tabs-bordered">

              <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
              </li>

              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
              </li>

              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
              </li>

              @if ($userDetails->usertype == 1 || $userDetails->usertype == 2)
                  <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-delete-account">Delete Account</button>
                  </li>
              @endif
              
            </ul>
            <div class="tab-content pt-2">

              <div class="tab-pane fade show active profile-overview" id="profile-overview">

                <h5 class="card-title">Profile Details</h5>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label ">Full Name</div>
                  <div class="col-lg-9 col-md-8">{{ $userDetails->firstname }} {{ $userDetails->lastname }}</div>
                </div>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Email</div>
                  <div class="col-lg-9 col-md-8">{{ $userDetails->email }}</div>
                </div>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Address</div>
                  <div class="col-lg-9 col-md-8">{{ $userDetails->address }}</div>
                </div>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Phone</div>
                  <div class="col-lg-9 col-md-8">{{ $userDetails->phonenum }}</div>
                </div>

                @if($userDetails->usertype == "1")
                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Class Code</div>
                  <div class="col-lg-9 col-md-8">{{ $userDetails->participants->participant_classcode }}</div>
                </div>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Group</div>
                  @if(isset($userDetails->participants->participant_groupID))
                  <div class="col-lg-9 col-md-8">{{ $userDetails->participants->participant_groupID }}</div>
                  @else
                  <div class="col-lg-9 col-md-8">Not set yet</div>
                  @endif
                </div>
                @elseif($userDetails->usertype == "2")
                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Matric Number</div>
                  <div class="col-lg-9 col-md-8">{{ $userDetails->members->matricNum }}</div>
                </div>
                @endif

              </div>

              <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                <!-- Profile Edit Form -->
                <form method="POST" name="editprofile" action="{{ route('editprofile') }}" enctype="multipart/form-data">
                  @csrf
                  <div class="row mb-3">
                    <label for="profilepic" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                    <div class="col-md-8 col-lg-9">
                      <img src="{{ asset('assets/img/profilepics/' . $userDetails->profilepic) }}" alt="Profile">
                      <input id="profilepic" type="file" class="form-control-file" name="profilepic">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="username" class="col-md-4 col-lg-3 col-form-label">Username</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="username" type="text" class="form-control" id="username" value="{{ $userDetails->username }}" readonly>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="firstname" class="col-md-4 col-lg-3 col-form-label">First Name</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="firstname" type="text" class="form-control" id="firstname" value="{{ $userDetails->firstname }}">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="lastname" class="col-md-4 col-lg-3 col-form-label">Last Name</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="lastname" type="text" class="form-control" id="lastname" value="{{ $userDetails->lastname }}">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="address" type="text" class="form-control" id="address" value="{{ $userDetails->address }}">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="phonenum" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="phonenum" type="text" class="form-control" id="phonenum" value="{{ $userDetails->phonenum }}">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="email" type="email" class="form-control" id="email" value="{{ $userDetails->email }}" readonly>
                    </div>
                  </div>

                  <div class="text-center">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                  </div>
                </form><!-- End Profile Edit Form -->

              </div>

              <div class="tab-pane fade pt-3" id="profile-change-password">
                <!-- Change Password Form -->
                <form name="changepassword" action="{{ route('changepassword') }}" method="POST">
                  @csrf
                  <div class="row mb-3">
                    <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="currentPassword" type="password" class="form-control @error('currentPassword') is-invalid @enderror" id="currentPassword">
                    </div>

                    @error('currentPassword')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>

                  <div class="row mb-3">
                    <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="newPassword" type="password" id="newPassword" class="form-control @error('newPassword') is-invalid @enderror" >
                    </div>
                    @error('newPassword')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>

                  <div class="row mb-3">
                    <label for="renewPassword_confirmation" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="renewPassword_confirmation" id="renewPassword_confirmation" type="password" class="form-control @error('renewPassword_confirmation') is-invalid @enderror" >
                    </div>
                    @error('renewPassword_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>

                  <div class="text-center">
                    <button type="submit" class="btn btn-primary">Change Password</button>
                  </div>
                </form><!-- End Change Password Form -->

              </div>

              <div class="tab-pane fade pt-3" id="profile-delete-account">
                <!-- delete account Form -->
                  <p class="card-text">Are you sure you want to delete your account? This action cannot be undone.</p>
                  <form name="changepassword" action="{{ route('deleteaccount') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                      <label for="password" class="col-md-4 col-lg-3 col-form-label">Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" id="password">
                      </div>
  
                      @error('password')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-danger">Delete Account</button>
                    </div>
                  </form>
              </div>

            </div><!-- End Bordered Tabs -->

          </div>
        </div>

      </div>
    </div>
  </section>

@endsection