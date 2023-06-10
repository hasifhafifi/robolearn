<ul class="sidebar-nav" id="sidebar-nav">
  
  @guest
    <li class="nav-item">
      <a class="nav-link " href="{{ route('login') }}">
        <i class="bi bi-arrow-bar-right"></i>
        <span>Sign In</span>
      </a>
    </li><!-- End signin Nav -->

    <li class="nav-item">
      <a class="nav-link " href="{{ route('register') }}">
        <i class="bi bi-arrow-bar-left"></i>
        <span>Sign Up</span>
      </a>
    </li><!-- End signup Nav -->
  @else
    @if(isset($isVerifiedMember) && $isVerifiedMember == false)
        <li class="nav-item">
        <a class="nav-link collapsed {{request()->routeIs('home') ? 'active' : ''}}" href="{{ route('home') }}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->
    @else
    <li class="nav-item">
      <a class="nav-link collapsed {{request()->routeIs('home') ? 'active' : ''}}" href="{{ route('home') }}">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li><!-- End Dashboard Nav -->
    
    @if(Auth::user()->usertype == '1')
    <li class="nav-item">
      <a class="nav-link collapsed" href="{{ route('livestreambyclass') }}">
        <i class="bi bi-camera-video"></i>
        <span>Livestream</span>
      </a>
    </li><!-- End livestream Page Nav -->
    @else
    <li class="nav-item">
      <a class="nav-link collapsed" href="{{ route('livestream') }}">
        <i class="bi bi-camera-video"></i>
        <span>Livestream</span>
      </a>
    </li><!-- End livestream Page Nav -->
    @endif

    @if(Auth::user()->usertype == '1')
    <li class="nav-item">
      <a class="nav-link collapsed" href="{{ route('module') }}">
        <i class="bi bi-folder"></i>
        <span>Module</span>
      </a>
    </li><!-- End Module Page Nav -->
    @else
    <li class="nav-item">
      <a class="nav-link collapsed" href="{{ route('activeclasslist') }}">
        <i class="bi bi-folder"></i>
        <span>Module</span>
      </a>
    </li><!-- End Module Page Nav -->
    @endif

    @if(Auth::user()->usertype == '1')
    <li class="nav-item">
      <a class="nav-link collapsed" href="{{ route('submissionIndex') }}">
        <i class="bi bi-upload"></i>
        <span>Submission</span>
      </a>
    </li>
    @else
    <li class="nav-item">
      <a class="nav-link collapsed" href="{{ route('activeclasslistsub') }}">
        <i class="bi bi-upload"></i>
        <span>Submission</span>
      </a>
    </li>
    @endif<!-- End Submission Page Nav -->

    @if(Auth::user()->usertype == '1')
    <li class="nav-item">
      <a class="nav-link collapsed" href="{{ route('forum.index') }}">
        <i class="bi bi-card-list"></i>
        <span>Forum</span>
      </a>
    </li><!-- End forum Page Nav -->
    @else
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#forum-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-card-list"></i><span>Forum</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="forum-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('forum.index') }}">
            <i class="bi bi-circle"></i><span>View Forum</span>
          </a>
        </li>
        <li>
          <a href="{{ route('forum.category.manage') }}">
            <i class="bi bi-circle"></i><span>Manage Forum</span>
          </a>
        </li>
      </ul>
    </li>
    @endif

    @if(Auth::user()->usertype == '1')
    <li class="nav-item">
      <a class="nav-link collapsed" href="pages-contact.html">
        <i class="bi bi-card-checklist"></i>
        <span>Report</span>
      </a>
    </li>
    @else
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#report-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-card-checklist"></i><span>Report</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="report-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('forum.index') }}">
            <i class="bi bi-circle"></i><span>Participant Progress</span>
          </a>
        </li>
        <li>
          <a href="{{ route('forum.category.manage') }}">
            <i class="bi bi-circle"></i><span>Tournament</span>
          </a>
        </li>
      </ul>
    </li>
    @endif
    <!-- End Report Page Nav -->

    @if(Auth::user()->usertype == '3')
    <li class="nav-item">
      <a class="nav-link collapsed" href="{{ route('viewmember') }}">
        <i class="bi bi-people"></i>
        <span>Manage Member</span>
      </a>
    </li><!-- End Manage Member Page Nav -->
    
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#manageparticipant-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-people"></i><span>Manage Participant</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="manageparticipant-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a  class="nav-link {{request()->routeIs('classroom') ? 'active' : ''}}" href="{{ route('classroom') }}">
            <i class="bi bi-circle"></i><span>Class</span>
          </a>
        </li>
        <li>
          <a href="{{ route('grouplist') }}">
            <i class="bi bi-circle"></i><span>Group</span>
          </a>
        </li>
        <li>
          <a href="{{ route('participant') }}">
            <i class="bi bi-circle"></i><span>Participant</span>
          </a>
        </li>
      </ul>
    </li><!-- End Manage Participant Nav -->
    @endif

    @if(session('isVerifiedMember') == true)
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#manageparticipant-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-people"></i><span>Manage Participant</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="manageparticipant-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('classroom') }}">
              <i class="bi bi-circle"></i><span>Class</span>
            </a>
          </li>
          <li>
            <a href="{{ route('grouplist') }}">
              <i class="bi bi-circle"></i><span>Group</span>
            </a>
          </li>
          <li>
            <a href="{{ route('participant') }}">
              <i class="bi bi-circle"></i><span>Participant</span>
            </a>
          </li>
        </ul>
      </li><!-- End Manage Participant Nav -->
    @endif

    <li class="nav-item">
      <a class="nav-link collapsed" href="{{ route('logout') }}"
        onclick="event.preventDefault();
        document.getElementById('logout-form').submit();">
        <i class="bi bi-box-arrow-in-right"></i>
        <span>Sign Out</span>
      </a>

      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
    </li><!-- End Logout Page Nav -->
    @endif
    @endguest
  </ul>