<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <!-- CSRF Token -->
     <meta name="csrf-token" content="{{ csrf_token() }}">

     {{-- <title>{{ config('app.name', 'Laravel') }}</title> --}}
 
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

    <title>
        @if (isset($thread_title))
            {{ $thread_title }} —
        @endif
        @if (isset($category))
            {{ $category->title }} —
        @endif
        {{ trans('forum::general.home_title') }}
    </title>

    <!-- Bootstrap (https://github.com/twbs/bootstrap) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Feather icons (https://github.com/feathericons/feather) -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <!-- Vue (https://github.com/vuejs/vue) -->
    @if (config('app.debug'))
        <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    @else
        <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
    @endif

    <!-- Axios (https://github.com/axios/axios) -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <!-- Pickr (https://github.com/Simonwep/pickr) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js"></script>

    <!-- Sortable (https://github.com/SortableJS/Sortable) -->
    <script src="//cdn.jsdelivr.net/npm/sortablejs@1.10.1/Sortable.min.js"></script>
    <!-- Vue.Draggable (https://github.com/SortableJS/Vue.Draggable) -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/Vue.Draggable/2.23.2/vuedraggable.umd.min.js"></script>

    <style>
    body
    {
        padding: 0;
        background: #f8fafc;
    }

    textarea
    {
        min-height: 200px;
    }

    table tr td
    {
        white-space: nowrap;
    }

    a
    {
        text-decoration: none;
    }

    .deleted
    {
        opacity: 0.65;
    }

    .shadow-sm
    {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card.category
    {
        margin-bottom: 1em;
    }

    .list-group .list-group
    {
        min-height: 1em;
        margin-top: 1em;
    }

    .btn svg.feather
    {
        width: 16px;
        height: 16px;
        stroke-width: 3px;
        vertical-align: -2px;
    }

    .modal-title svg.feather
    {
        margin-right: .5em;
        vertical-align: -3px;
    }

    .category .subcategories
    {
        background: #fff;
    }

    .category > .list-group-item
    {
        z-index: 1000;
    }

    .category .subcategories .list-group-item:first-child
    {
        border-radius: 0;
    }

    .timestamp
    {
        border-bottom: 1px dotted var(--bs-gray);
        cursor: help;
    }

    .fixed-bottom-right
    {
        position: fixed;
        right: 0;
        bottom: 0;
    }

    .fade-enter-active, .fade-leave-active
    {
        transition: opacity .3s;
    }
    .fade-enter, .fade-leave-to
    {
        opacity: 0;
    }

    .mask
    {
        display: none;
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background: rgba(50, 50, 50, .2);
        opacity: 0;
        transition: opacity .2s ease;
        z-index: 1020;
    }
    .mask.show
    {
        opacity: 1;
    }

    .form-check
    {
        user-select: none;
    }

    .sortable-chosen
    {
        background: var(--bs-light);
    }


    </style>
</head>
<body>
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-left">
        <i class="bi bi-list toggle-sidebar-btn"></i>
        <a href="{{ route('home') }}" class="logo d-flex align-items-center">
            <img src="{{ asset('assets/img/robolearn-crop.png') }}" alt="" height="50%" width="60%">
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

                <a class="nav-link nav-icon" href="{{ route('chat') }}">
                    <i class="bi bi-chat-left-text"></i>
                    <span class="badge bg-success badge-number">{{ App\Models\ChMessage::where('to_id', auth()->id())->where('seen', 0)->count() }}</span>
                </a><!-- End Messages Icon -->

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
    <main id="main" class="main">
    <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">

        <div id="mainn" class="container">
            @include('forum::partials.breadcrumbs')
            @include('forum::partials.alerts')
            <section class="section">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body">
                        @yield('content')
                      </div>
                    </div>
                  </div>
                </div>
            </section>
        </div>
          </div>
        </div>
    </div>
    </main>
    <div class="mask"></div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.18/vue.min.js"></script>
    <script>
    new Vue({
        el: '.v-navbar',
        name: 'Navbar',
        data: {
            isCollapsed: true,
            isUserDropdownCollapsed: true
        },
        methods: {
            onWindowClick (event) {
                const ignore = ['navbar-toggler', 'navbar-toggler-icon', 'dropdown-toggle'];
                if (ignore.some(className => event.target.classList.contains(className))) return;
                if (! this.isCollapsed) this.isCollapsed = true;
                if (! this.isUserDropdownCollapsed) this.isUserDropdownCollapsed = true;
            }
        },
        created: function () {
            window.addEventListener('click', this.onWindowClick);
        }
    });

    const mask = document.querySelector('.mask');

    function findModal (key)
    {
        const modal = document.querySelector(`[data-modal=${key}]`);

        if (! modal) throw `Attempted to open modal '${key}' but no such modal found.`;

        return modal;
    }

    function openModal (modal)
    {
        modal.style.display = 'block';
        mask.style.display = 'block';
        setTimeout(function()
        {
            modal.classList.add('show');
            mask.classList.add('show');
        }, 200);
    }

    document.querySelectorAll('[data-open-modal]').forEach(item =>
    {
        item.addEventListener('click', event =>
        {
            event.preventDefault();

            openModal(findModal(event.currentTarget.dataset.openModal));
        });
    });

    document.querySelectorAll('[data-modal]').forEach(modal =>
    {
        modal.addEventListener('click', event =>
        {
            if (! event.target.hasAttribute('data-close-modal')) return;

            modal.classList.remove('show');
            mask.classList.remove('show');
            setTimeout(function()
            {
                modal.style.display = 'none';
                mask.style.display = 'none';
            }, 200);
        });
    });

    document.querySelectorAll('[data-dismiss]').forEach(item =>
    {
        item.addEventListener('click', event => event.currentTarget.parentElement.style.display = 'none');
    });

    document.addEventListener('DOMContentLoaded', event =>
    {
        const hash = window.location.hash.substr(1);
        if (hash.startsWith('modal='))
        {
            openModal(findModal(hash.replace('modal=','')));
        }

        feather.replace();

        const input = document.querySelector('input[name=color]');

        if (! input) return;

        const pickr = Pickr.create({
            el: '.pickr',
            theme: 'classic',
            default: input.value || null,

            swatches: [
                '{{ config('forum.web.default_category_color') }}',
                '#f44336',
                '#e91e63',
                '#9c27b0',
                '#673ab7',
                '#3f51b5',
                '#2196f3',
                '#03a9f4',
                '#00bcd4',
                '#009688',
                '#4caf50',
                '#8bc34a',
                '#cddc39',
                '#ffeb3b',
                '#ffc107'
            ],

            components: {
                preview: true,
                hue: true,
                interaction: {
                    input: true,
                    save: true
                }
            },

            strings: {
                save: 'Apply'
            }
        });

        pickr
            .on('save', instance => pickr.hide())
            .on('clear', instance =>
            {
                input.value = '';
                input.dispatchEvent(new Event('change'));
            })
            .on('cancel', instance =>
            {
                const selectedColor = instance
                    .getSelectedColor()
                    .toHEXA()
                    .toString();

                input.value = selectedColor;
                input.dispatchEvent(new Event('change'));
            })
            .on('change', (color, instance) =>
            {
                const selectedColor = color
                    .toHEXA()
                    .toString();

                input.value = selectedColor;
                input.dispatchEvent(new Event('change'));
            });
    });
    </script>
    @yield('footer')
</body>
</html>

