@extends('layouts.app')
@section('content')
<div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section dashboard">
    <div class="row">

      <!-- Left side columns -->
      <div class="col-lg-12">
        <div class="row">

          <!-- Recent Sales -->
          <div class="col-12">
            <div class="card recent-sales overflow-auto">

              @php
              // get the profile picture link
                $linkProfilePic = Auth::user()->profilepic;   
              @endphp

              <div class="card-body">
                <!-- Check for usertype -->
                @if (Auth::user()->usertype == '1')
                <h5 class="card-title">Robolearn Participant</h5>
                @elseif (Auth::user()->usertype == '2')
                <h5 class="card-title">Robotic Club Member</h5>
                @elseif (Auth::user()->usertype == '3')
                <h5 class="card-title">Admin</h5>
                @endif
                <div class="row">
                  <div class="col-lg-4 mb-4">
                    <div class="card" style="width: 18rem; height:18rem;">
                      <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                      <img class="card-img-top rounded-circle" src="{{ asset('assets/img/profilepics/' . $linkProfilePic) }}" alt="Profile Picture">
                      <div class="card-body">
                        <h5 class="card-title text-center">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h5>
                      </div>
                    </div>
                    </div>
                  </div>

                <!-- Check if member is verified or not -->
                @if(isset($isVerifiedMember) && $isVerifiedMember == false)
                  <div class="col-lg-6 mb-4">
                    <div class="card" style="width: 40rem;">
                      <div class="card-body">
                        <h5 class="card-title">Detail</h5>
                        <p class="card-text">Your account is still not verified by the Admin.</p>
                      </div>
                    </div>
                  </div>
                @else
                  <div class="col-lg-6 mb-4">
                    <div class="card" style="width: 40rem;">
                      <div class="card-body">
                        <h5 class="card-title">User Detail</h5>
                        <p class="card-text"><strong>Username : </strong>{{$user->username}}</p>
                        <p class="card-text"><strong>Email : </strong>{{$user->email}}</p>
                        <p class="card-text"><strong>Address : </strong>{{$user->address}}</p>
                        <p class="card-text"><strong>Phone Number : </strong>{{$user->phonenum}}</p>
                        @if(Auth::user()->usertype == '1')
                        <p class="card-text"><strong>Class : </strong>{{$user->className}}</p>
                        @endif
                        <a href="{{route('profile')}}" class="btn btn-primary">Edit Profile</a>
                      </div>
                    </div>
                  </div>
                @endif
                </div>
                
                <!-- if usertype is participant, display the module name and progress -->
                @if(Auth::user()->usertype == '1')
                <!-- cards -->
                @if(!$modules->isEmpty())
                <h5 class="card-title">Learning Progress</h5>
                <div class="row">
                  <!-- Sales Card -->
                  @foreach($modules as $module)
                  <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">

                      <div class="card-body">
                        <h5 class="card-title">{{ $module->moduleName }}</h5>

                        <div class="d-flex align-items-center">
                          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <a href="{{ route('viewmoduleparticipant', ['id' => $module->id]) }}"><i class="bi bi-folder"></i></a>
                          </div>
                          <div class="ps-3">
                            <h6>{{ $module->percentage }}%</h6>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                  @endforeach
                  @endif
                </div>
                @endif
              </div>
              
            </div>
          </div><!-- End Recent Sales -->

        </div>
      </div><!-- End Left side columns -->

    </div>
  </section>
@endsection
