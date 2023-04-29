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
                <h5 class="card-title">Sign In</h5>
                <form method="POST" action="{{ route('login') }}">
                  @csrf

                  <!-- Username input -->
                  <div class="form-outline mb-4">
                    <label class="form-label" for="form3Example3">Username</label>
                    <input type="text" id="username" name="username" class="form-control" />
                  </div>
  
                  <!-- Password input -->
                  <div class="form-outline mb-4">
                    <label class="form-label" for="form3Example4">Password</label>
                    <input type="password" id="password" name="password" class="form-control" />
                  </div>
                  
                  <div class="row mb-4">
                    <div class="col d-flex justify-content-center">
                      <!-- Checkbox -->
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="remember" name="remember"/>
                        <label class="form-check-label" for="remember"> Remember me </label>
                      </div>
                    </div>

                    <div class="col">
                      <!-- Simple link -->
                      @if (Route::has('password.request'))
                          <a href="{{ route('password.request') }}">
                              {{ __('Forgot Your Password?') }}
                          </a>
                      @endif
                      {{-- <a href="#!">Forgot password?</a> --}}
                    </div>
                  </div>


                  <!-- Submit button -->
                  <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-block mb-4">
                        Sign In
                    </button>
                  </div>

                  <!-- sign in buttons -->
                  <div class="text-center">
                    <p>Does not have an account yet? <a href="{{route('register')}}">Sign Up</a></p>
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