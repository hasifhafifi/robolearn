@extends ('layouts.app')
@section('content')
<div class="pagetitle">
  <h1>Participant Profile</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('participant') }}">Manage Participant</a></li>
      <li class="breadcrumb-item active">Profile</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section profile">
    <div class="row">
      <div class="col-xl-4">

        <div class="card">
          <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

            <img src="{{ asset('assets/img/profilepics/' . $user->profilepic) }}" alt="Profile" class="rounded-circle">
            <h2>{{ $user->username }}</h2>
            
            @if($user->usertype == 1)
              <h3>Participant</h3>
            @elseif($user->usertype == 2)
              <h3>Club Member</h3>
            @elseif($user->usertype == 3)
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

            </ul>
            <div class="tab-content pt-2">

              <div class="tab-pane fade show active profile-overview" id="profile-overview">

                <h5 class="card-title">Profile Details</h5>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label ">Full Name</div>
                  <div class="col-lg-9 col-md-8">{{ $user->firstname }} {{ $user->lastname }}</div>
                </div>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Email</div>
                  <div class="col-lg-9 col-md-8">{{ $user->email }}</div>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-md-4 label">Class Code</div>
                    <div class="col-lg-9 col-md-8">{{ $user->participants->participant_classcode }}</div>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-md-4 label">Group</div>
                    @if (isset($user->participants->participant_groupID))
                        <div class="col-lg-9 col-md-8">{{ $user->group->name }}</div>
                    @else
                        <div class="col-lg-9 col-md-8">Not Set Yet</div>
                    @endif
                </div>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Address</div>
                  <div class="col-lg-9 col-md-8">{{ $user->address }}</div>
                </div>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Phone</div>
                  <div class="col-lg-9 col-md-8">{{ $user->phonenum }}</div>
                </div>

              </div>

            </div><!-- End Bordered Tabs -->

          </div>
        </div>

      </div>
    </div>
  </section>

@endsection