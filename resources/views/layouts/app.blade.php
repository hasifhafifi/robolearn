<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}

    <!-- Favicons -->
    <link href="{{ asset('assets/img/roboticon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-robot-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body>
    <!-- welcomelayout.blade -->
     <!-- ======= Header ======= -->
        <header id="header" class="header fixed-top d-flex align-items-center">

            <div class="d-flex align-items-center justify-content-between">
            <i class="bi bi-list toggle-sidebar-btn"></i>
            <a href="{{ route('home') }}" class="logo d-flex align-items-center">
                <img src="{{ asset('assets/img/robolearn.png') }}" alt="">
                {{-- <span class="d-none d-lg-block">NiceAdmin</span> --}}
            </a>
            </div><!-- End Logo --> 

            <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item pe-3">
                            <a class="nav-link d-none d-md-block ps-2 text-white" href="{{ route('login') }}">{{ __('Sign In') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item pe-3">
                            <a class="nav-link d-none d-md-block ps-2 text-white" href="{{ route('register') }}">{{ __('Sign Up') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">

                    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        <span class="badge bg-primary badge-number">4</span>
                    </a><!-- End Notification Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                        <li class="dropdown-header">
                        You have 4 new notifications
                        <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                        </li>
                        <li>
                        <hr class="dropdown-divider">
                        </li>

                        <li class="notification-item">
                        <i class="bi bi-exclamation-circle text-warning"></i>
                        <div>
                            <h4>Lorem Ipsum</h4>
                            <p>Quae dolorem earum veritatis oditseno</p>
                            <p>30 min. ago</p>
                        </div>
                        </li>

                        <li>
                        <hr class="dropdown-divider">
                        </li>

                        <li class="notification-item">
                        <i class="bi bi-x-circle text-danger"></i>
                        <div>
                            <h4>Atque rerum nesciunt</h4>
                            <p>Quae dolorem earum veritatis oditseno</p>
                            <p>1 hr. ago</p>
                        </div>
                        </li>

                        <li>
                        <hr class="dropdown-divider">
                        </li>

                        <li class="notification-item">
                        <i class="bi bi-check-circle text-success"></i>
                        <div>
                            <h4>Sit rerum fuga</h4>
                            <p>Quae dolorem earum veritatis oditseno</p>
                            <p>2 hrs. ago</p>
                        </div>
                        </li>

                        <li>
                        <hr class="dropdown-divider">
                        </li>

                        <li class="notification-item">
                        <i class="bi bi-info-circle text-primary"></i>
                        <div>
                            <h4>Dicta reprehenderit</h4>
                            <p>Quae dolorem earum veritatis oditseno</p>
                            <p>4 hrs. ago</p>
                        </div>
                        </li>

                        <li>
                        <hr class="dropdown-divider">
                        </li>
                        <li class="dropdown-footer">
                        <a href="#">Show all notifications</a>
                        </li>

                    </ul><!-- End Notification Dropdown Items -->

                    </li><!-- End Notification Nav -->

                    <li class="nav-item dropdown">

                    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-chat-left-text"></i>
                        <span class="badge bg-success badge-number">3</span>
                    </a><!-- End Messages Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
                        <li class="dropdown-header">
                        You have 3 new messages
                        <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                        </li>
                        <li>
                        <hr class="dropdown-divider">
                        </li>

                        <li class="message-item">
                        <a href="#">
                            <img src="{{ asset('assets/img/messages-1.jpg') }}" alt="" class="rounded-circle">
                            <div>
                            <h4>Maria Hudson</h4>
                            <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                            <p>4 hrs. ago</p>
                            </div>
                        </a>
                        </li>
                        <li>
                        <hr class="dropdown-divider">
                        </li>

                        <li class="message-item">
                        <a href="#">
                            <img src="{{ asset('assets/img/messages-2.jpg') }}" alt="" class="rounded-circle">
                            <div>
                            <h4>Anna Nelson</h4>
                            <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                            <p>6 hrs. ago</p>
                            </div>
                        </a>
                        </li>
                        <li>
                        <hr class="dropdown-divider">
                        </li>

                        <li class="message-item">
                        <a href="#">
                            <img src="{{ asset('assets/img/messages-3.jpg') }}" alt="" class="rounded-circle">
                            <div>
                            <h4>David Muldon</h4>
                            <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                            <p>8 hrs. ago</p>
                            </div>
                        </a>
                        </li>
                        <li>
                        <hr class="dropdown-divider">
                        </li>

                        <li class="dropdown-footer">
                        <a href="#">Show all messages</a>
                        </li>

                    </ul><!-- End Messages Dropdown Items -->

                    </li><!-- End Messages Nav -->

                    <li class="nav-item dropdown pe-3">

                    @php
                        $linkProfilePic = Auth::user()->profilepic;   
                    @endphp

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="{{ asset('assets/img/profilepics/' . $linkProfilePic) }}" alt="Profile" class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->username }}</span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                        <h6>{{ Auth::user()->username }}</h6>
                        @if (Auth::user()->usertype == '1')
                        <span>Participant</span>
                        @elseif (Auth::user()->usertype == '2')
                        <span>Robotic Club Member</span>
                        @elseif (Auth::user()->usertype == '3')
                        <span>Admin</span>
                        @endif
                        </li>
                        <li>
                        <hr class="dropdown-divider">
                        </li>

                        <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{route('profile')}}">
                            <i class="bi bi-person"></i>
                            <span>My Profile</span>
                        </a>
                        </li>
                        <li>
                        <hr class="dropdown-divider">
                        </li>

                        <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form-app').submit();">
                            
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Sign Out</span>
                        </a>

                        <form id="logout-form-app" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                    </li><!-- End Profile Nav -->
            </ul>
            @endguest
            </nav><!-- End Icons Navigation -->

        </header><!-- End Header -->

        <!-- ======= Sidebar ======= -->
        <aside id="sidebar" class="sidebar">
            @include('layouts.sidebar')
        </aside>
        <!-- End Sidebar-->

        <!--end welcomelayout -->

         <main id="main" class="main">
            <!-- Display success/error message -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main><!-- End #main -->

         <!-- ======= Footer ======= -->
            <footer id="footer" class="footer">
                <div class="copyright">
                &copy; Copyright <strong><span>RoboLearn</span></strong>. All Rights Reserved
                </div>
                <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
                </div>
            </footer><!-- End Footer -->

            <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

            <!-- Vendor JS Files -->
            <script src=" {{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
            <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
            <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
            <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
            <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
            <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
            <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
            <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

            <!-- Template Main JS File -->
            <script src="{{ asset('assets/js/main.js') }}"></script>
    </div>
</body>
</html>
