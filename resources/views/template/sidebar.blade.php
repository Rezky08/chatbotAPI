@section('sidebar')
<aside class="menu">
    <ul class="menu-list">
        <li><a href="{{ url('/dashboard') }}">
                <span class="icon has-text-info">
                    <i class="fas fa-home"></i>
                </span>
                Dashboard
            </a></li>
    </ul>
    <span class="menu-label">App</span>
    <ul class="menu-list">
        <li><a href="{{ url('/app-key') }}">
                <span class="icon has-text-info">
                    <i class="fas fa-key"></i>
                </span>
                Application Key
            </a></li>
        <li><a href="{{ url('/telegram') }}">
                <span class="icon has-text-info">
                    <i class="fab fa-telegram"></i>
                </span>
                Telegram
            </a></li>
    </ul>
    <span class="menu-label">App Report</span>
    <ul class="menu-list">
        <li><a href="{{ url('/') }}">
                <span class="icon has-text-info">
                    <i class="fas fa-user"></i>
                </span>
                Account
            </a></li>
        <li><a href="{{ url('/') }}">
                <span class="icon has-text-info">
                    <i class="fas fa-comment"></i>
                </span>
                Chats
            </a></li>
    </ul>
    <span class="menu-label">Data</span>
    <ul class="menu-list">
        <li><a href="{{ url('/') }}">
                <span class="icon has-text-info">
                    <i class="fa fa-sticky-note"></i>
                </span>
                Data Set
            </a></li>
        <li><a href="{{ url('/') }}">
                <span class="icon has-text-info">
                    <i class="fas fa-tag"></i>
                </span>
                Labels
            </a></li>
    </ul>
</aside>
@endsection
