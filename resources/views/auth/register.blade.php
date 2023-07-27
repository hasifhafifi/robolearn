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
            <img src="{{ asset('assets/img/robotimg.png') }}" alt="robot" height="100px;">
          </div>
  
          <div class="col-lg-6 mb-5 mb-lg-0">
            <div class="card">
              <div class="card-body py-5 px-md-5">
                <h5 class="card-title">Sign Up</h5>
                <!-- send form data to register route -->
                <form method="POST" action="{{ route('register') }}">
                  @csrf
                    <div class="form-outline mb-4">
                        <label class="col-sm-4 col-form-label">Sign Up As:</label>
                        <div class="col-sm-12">
                          <!-- To show or hide the field based in the usertype(for default use the participant) -->
                            <select class="form-select" name="usertype" id="usertype" aria-label="Default select example" onchange="showHide()">
                            <option selected value=1>Participant</option>
                            <option value="2">Robotic Club Member</option>
                            </select>
                        </div>
                    </div>

                  <!-- Username input -->
                  <div class="form-outline mb-4">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control @error('username') is-invalid @enderror" value="{{old('username')}}" required/>
                    @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>

                  <!-- 2 column grid layout with text inputs for the first and last names -->
                  <div class="row">
                    <div class="col-md-6 mb-4">
                      <div class="form-outline">
                        <label class="form-label" for="firstname">First name</label>
                        <input type="text" id="firstname" name="firstname" value="{{old('firstname')}}" class="form-control @error('firstname') is-invalid @enderror" required/>
                        @error('firstname')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>
                    <div class="col-md-6 mb-4">
                      <div class="form-outline">
                        <label class="form-label" for="lastname">Last name</label>
                        <input type="text" id="lastname" name="lastname" value="{{old('lastname')}}" class="form-control @error('lastname') is-invalid @enderror" required/>
                        @error('lastname')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                      </div>
                    </div>
                  </div>
  
                  <!-- Email input -->
                  <div class="form-outline mb-4">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{old('email')}}" class="form-control @error('email') is-invalid @enderror" required/>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>

                  <!-- address input -->
                  <div class="form-outline mb-4">
                    <label class="form-label" for="address">Address</label>
                    <input type="text" id="address" name="address" value="{{old('address')}}" class="form-control @error('address') is-invalid @enderror" required/>
                    @error('address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>


                  <!-- phone number input -->
                  <div class="form-outline mb-4">
                    <label class="form-label" for="phonenum">Phone Number</label>
                    <input type="text" id="phonenum" name="phonenum" value="{{old('phonenum')}}" class="form-control @error('phonenum') is-invalid @enderror" required/>
                    @error('phonenum')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>
  
                  <!-- Password input -->
                  <div class="form-outline mb-4">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required/>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>
  
                  <!-- class code input for participant -->
                  <div class="form-outline mb-4" id="classcodediv">
                    <label class="form-label" for="classcode">Class Code</label>
                    <input type="text" id="classcode" name="classcode" value="{{old('classcode')}}" class="form-control @error('classcode') is-invalid @enderror" />
                    @error('classcode')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>

                  <!-- matric number input for member -->
                  <div class="form-outline mb-4" style="display:none;" id="matricnumdiv">
                    <label class="form-label" for="matricnum">Matric Number</label>
                    <input type="text" id="matricnum" name="matricnum" value="{{old('classcode')}}" class="form-control @error('matricnum') is-invalid @enderror" />
                    @error('matricnum')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
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

  <script>
    // to display/hide the field based on selected usertype
    function showHide() {
      var selectValue = document.getElementById("usertype").value;
      if (selectValue == "1") {
        document.getElementById("classcodediv").style.display = "block";
        document.getElementById("matricnumdiv").style.display = "none";
        document.getElementById("classcode").required = true;
        document.getElementById("matricnum").required = false;
      } else {
        document.getElementById("classcodediv").style.display = "none";
        document.getElementById("matricnumdiv").style.display = "block";
        document.getElementById("classcode").required = false;
        document.getElementById("matricnum").required = true;
      }
    }
  </script>

@endsection