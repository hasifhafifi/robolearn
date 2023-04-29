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
        <a class="nav-link " href="{{ route('home') }}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->
    @else
    <li class="nav-item">
      <a class="nav-link " href="{{ route('home') }}">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li><!-- End Dashboard Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" href="users-profile.html">
        <i class="bi bi-camera-video"></i>
        <span>Livestream</span>
      </a>
    </li><!-- End livestream Page Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" href="pages-faq.html">
        <i class="bi bi-folder"></i>
        <span>Module</span>
      </a>
    </li><!-- End Module Page Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" href="pages-contact.html">
        <i class="bi bi-card-checklist"></i>
        <span>Report</span>
      </a>
    </li><!-- End Report Page Nav -->

    @if(Auth::user()->usertype == '3')
    <li class="nav-item">
      <a class="nav-link collapsed" href="{{ route('viewmember') }}">
        <i class="bi bi-people"></i>
        <span>Manage Member</span>
      </a>
    </li><!-- End Manage Member Page Nav -->
    
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#manageparticipant-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-people""></i><span>Manage Participant</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="manageparticipant-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('classroom') }}">
            <i class="bi bi-circle"></i><span>Class</span>
          </a>
        </li>
        <li>
          <a href="{{ route('group') }}">
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

    @if(isset($isVerifiedMember) && $isVerifiedMember == true)
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#manageparticipant-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-people""></i><span>Manage Participant</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="manageparticipant-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('classroom') }}">
              <i class="bi bi-circle"></i><span>Class</span>
            </a>
          </li>
          <li>
            <a href="{{ route('group') }}">
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