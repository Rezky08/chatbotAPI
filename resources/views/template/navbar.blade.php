@section('navbar')
    <nav class="navbar is-info">
        <div class="navbar-brand">
            <a class="navbar-item" href="{{ url('/dashboard') }}">
                <img src="{{ asset('img/chatboticon.svg') }}" alt="Chat Bot Icon" class="image is-32x32"> <span
                    class="has-text-rubik has-text-weight-bold">&nbsp;Chat Bot</span>
            </a>
            <a role="button" class="navbar-burger" data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>
        <div class="navbar-menu" id="navbarBasicExample">
            <div class="navbar-end is-hidden-desktop">
                <div class="navbar-item">
                    {{-- <a href="{{ url('/profile') }}" class="navbar-item">Profile</a> --}}
                    <a href="{{ url('/logout') }}" class="navbar-item">
                        <span>
                            <i class="fas fa-sign-out"></i>
                        </span>
                        Logout</a>
                </div>
            </div>
            <div class="navbar-end is-hidden-touch">
                <div class="navbar-item">
                    <div class="dropdown is-hoverable is-right">
                        <span class="dropdown-trigger is-size-4">
                            <i class="fa fa-user-circle"></i>
                        </span>
                        <div class="dropdown-menu">
                            <div class="dropdown-content">
                                {{-- <a href="{{ url('/profile') }}" class="dropdown-item">Profile</a> --}}
                                {{-- <hr class="dropdown-divider"> --}}
                                <a href="{{ url('/logout') }}" class="dropdown-item">
                                    <span>
                                        <i class="fas fa-sign-out"></i>
                                    </span>
                                    Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
@endsection
