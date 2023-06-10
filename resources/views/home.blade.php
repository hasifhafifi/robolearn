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

              {{-- <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                  </li>

                  <li><a class="dropdown-item" href="#">Today</a></li>
                  <li><a class="dropdown-item" href="#">This Month</a></li>
                  <li><a class="dropdown-item" href="#">This Year</a></li>
                </ul>
              </div> --}}

              @php
                $linkProfilePic = Auth::user()->profilepic;   
              @endphp

              <div class="card-body">
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
                        <a href="{{route('profile')}}" class="btn btn-primary">Edit Profile</a>
                      </div>
                    </div>
                  </div>
                @endif
                </div>
                
                @if(Auth::user()->usertype == '1')
                <!-- cards -->
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
                  <!-- End Sales Card -->

                  <!-- Revenue Card -->
                  {{-- <div class="col-xxl-4 col-md-6">
                    <div class="card info-card revenue-card">

                      <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                          <li class="dropdown-header text-start">
                            <h6>Filter</h6>
                          </li>

                          <li><a class="dropdown-item" href="#">Today</a></li>
                          <li><a class="dropdown-item" href="#">This Month</a></li>
                          <li><a class="dropdown-item" href="#">This Year</a></li>
                        </ul>
                      </div>

                      <div class="card-body">
                        <h5 class="card-title">Revenue <span>| This Month</span></h5>

                        <div class="d-flex align-items-center">
                          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-currency-dollar"></i>
                          </div>
                          <div class="ps-3">
                            <h6>$3,264</h6>
                            <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span>

                          </div>
                        </div>
                      </div>

                    </div>
                  </div><!-- End Revenue Card -->

                   <!-- Customers Card -->
                  <div class="col-xxl-4 col-xl-12">

                    <div class="card info-card customers-card">

                      <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                          <li class="dropdown-header text-start">
                            <h6>Filter</h6>
                          </li>

                          <li><a class="dropdown-item" href="#">Today</a></li>
                          <li><a class="dropdown-item" href="#">This Month</a></li>
                          <li><a class="dropdown-item" href="#">This Year</a></li>
                        </ul>
                      </div>

                      <div class="card-body">
                        <h5 class="card-title">Customers <span>| This Year</span></h5>

                        <div class="d-flex align-items-center">
                          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-people"></i>
                          </div>
                          <div class="ps-3">
                            <h6>1244</h6>
                            <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span>

                          </div>
                        </div>

                      </div>
                    </div>

                  </div><!-- End Customers Card --> --}}
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
