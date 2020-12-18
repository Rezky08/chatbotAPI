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
        <span class="menu-label">Application</span>
        <ul class="menu-list">
            <li>
                <a href="{{ url('/application/key') }}">
                    <span class="icon has-text-info">
                        <i class="fas fa-key"></i>
                    </span>
                    Application Key
                </a>
            </li>
            <li>
                <a href="{{ url('/application/chat', []) }}">
                    <span class="icon has-text-info">
                        <i class="fa fa-comment"></i>
                    </span>
                    <span>Chat</span>
                </a>
            </li>

        </ul>
        <span class="menu-label">Telegram</span>
        <ul class="menu-list">
            <li>
                <a href="{{ url('/telegram') }}">
                    <span class="icon has-text-info">
                        <i class="fab fa-telegram"></i>
                    </span>
                    Telegram Bot
                </a>
            </li>
            <li><a href="{{ url('/telegram/account') }}">
                    <span class="icon has-text-info">
                        <i class="fas fa-user"></i>
                    </span>
                    Account
                </a></li>
            <li><a href="{{ url('/telegram/chat') }}">
                    <span class="icon has-text-info">
                        <i class="fas fa-comment"></i>
                    </span>
                    Chat
                </a></li>
        </ul>
        <span class="menu-label">Data</span>
        <ul class="menu-list">
            <li>
                <a href="{{ url('/question') }}">
                    <span class="icon has-text-info">
                        <i class="fa fa-question"></i>
                    </span>
                    Question
                </a>
            </li>
            <li>
                <a href="{{ url('/') }}">
                    <span class="icon has-text-info">
                        <i class="fa fa-exclamation"></i>
                    </span>
                    Answer
                </a>
            </li>
            <li>
                <a href="{{ url('/') }}">
                    <span class="icon has-text-info">
                        <i class="fas fa-tag"></i>
                    </span>
                    Labels
                </a>
            </li>
        </ul>
    </aside>
@endsection
