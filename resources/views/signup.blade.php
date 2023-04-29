@extends ('layouts.app')
@section('content')

<!-- Section: Design Block -->
<section class="">
    <!-- Jumbotron -->
    <div class="px-4 py-5 px-md-5 text-center text-lg-start" style="background-color: hsl(0, 0%, 96%)">
      <div class="container">
        <div class="row gx-lg-5 align-items-center">
          <div class="col-lg-6 mb-5 mb-lg-0 text-center">
            <h1 class="display-3 fw-bold ls-tight">
              Welcome <br/> To <br />
              <span class="text-danger">RoboLearn</span>
            </h1>
            {{-- <p style="color: hsl(217, 10%, 50.8%)">
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Eveniet, itaque accusantium odio, soluta, corrupti aliquam
              quibusdam tempora at cupiditate quis eum maiores libero
              veritatis? Dicta facilis sint aliquid ipsum atque?
            </p> --}}
            <img src="{{ asset('assets/img/robotimg.png') }}" alt="robot" height="100px;">
          </div>
  
          <div class="col-lg-6 mb-5 mb-lg-0">
            <div class="card">
              <div class="card-body py-5 px-md-5">
                <h5 class="card-title">Sign Up</h5>
                <form method="POST" action="{{ route('register') }}">
                  @csrf
                    <div class="form-outline mb-4">
                        <label class="col-sm-4 col-form-label">Sign In as</label>
                        <div class="col-sm-12">
                            <select class="form-select" name="usertype" id="usertype" aria-label="Default select example">
                            <option selected value=1>Participant</option>
                            <option value="2">Robotic Club Member</option>
                            </select>
                        </div>
                    </div>

                  <!-- Username input -->
                  <div class="form-outline mb-4">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" />
                  </div>

                  <!-- 2 column grid layout with text inputs for the first and last names -->
                  <div class="row">
                    <div class="col-md-6 mb-4">
                      <div class="form-outline">
                        <label class="form-label" for="firstname">First name</label>
                        <input type="text" id="firstname" name="firstname" class="form-control" />
                      </div>
                    </div>
                    <div class="col-md-6 mb-4">
                      <div class="form-outline">
                        <label class="form-label" for="lastname">Last name</label>
                        <input type="text" id="lastname" name="lastname" class="form-control" />
                      </div>
                    </div>
                  </div>
  
                  <!-- Email input -->
                  <div class="form-outline mb-4">
                    <label class="form-label" for="email">Email address</label>
                    <input type="email" id="email" name="email" class="form-control" />
                  </div>

                  <!-- phone number input -->
                  <div class="form-outline mb-4">
                    <label class="form-label" for="phonenum">Phone Number</label>
                    <input type="text" id="phonenum" name="phonenum" class="form-control" />
                  </div>
  
                  <!-- Password input -->
                  <div class="form-outline mb-4">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" />
                  </div>
  
                  <!-- class code input -->
                  <div class="form-outline mb-4">
                    <label class="form-label" for="classcode">Class Code</label>
                    <input type="text" id="classcode" name="classcode" class="form-control" />
                  </div>
  
                  <!-- Submit button -->
                  <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-block mb-4">
                        Sign up
                    </button>
                  </div>

                  <!-- sign in buttons -->
                  <div class="text-center">
                    <p>Already have an account? <a href="{{ route('login') }}">Sign In</a></p>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Jumbotron -->
  </section>
  <!-- Section: Design Block -->

@endsection