@extends('template.auth_template')

@section('head')
    @parent
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endsection



@section('main')
    {{-- search --}}
    <form action="" method="get" id="search" class="my-3">
        <div class="columns">
            <div class="column is-half">
                <select name="telegram_bot" id="telegram-bot" style="width: 100%">
                    <option value=""></option>
                    @foreach ($telegrams as $key => $telegram)
                        <option value="{{ $telegram->id }}" @if (Request::get('telegram_bot') == $telegram->id)
                            selected
                    @endif
                    >{{ $telegram->name }} - {{ $telegram->username }}</option>
                    @endforeach
                </select>
            </div>
            <div class="column is-half">
                <select name="telegram_account" id="telegram-account" style="width: 100%">
                    <option value=""></option>
                    @foreach ($accounts as $key => $account)
                        <option value="{{ $account->id }}" @if (Request::get('telegram_account') == $account->id)
                            selected
                    @endif>{{ $account->name }} - {{ $account->username }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    {{-- table --}}
    <div class="box">
        <div class="table-container">
            <table class="table is-fullwidth">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>App Name</th>
                        <th>Text</th>
                        <th>Response</th>
                        <th>Time Received</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($chats as $key => $chat)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $chat->application->client->name }}</td>
                            <td>{{ $chat->text }}</td>
                            <td>{{ $chat->response }}</td>
                            <td>{{ $chat->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- modal --}}
    <div class="container is-clipped">
        <div class="modal" id="modal">
            <div class="modal-background">
            </div>
            <div class="modal-content is-clipped">
                <div class="box">
                    <span class="block has-text-rubik is-size-4 has-text-weight-semibold">Telegram Bot</span>
                    <div id="form-bot">
                        <div class="field my-3">
                            <div class="control has-icons-left">
                                <input type="text" class="input" name="bot_token" id="bot_token">
                                <span class="icon is-left">
                                    <i class="fas fa-key"></i>
                                </span>
                                <p class="help is-danger is-hidden" id="bot_error"></p>
                            </div>
                        </div>
                        <div class="block has-text-right">
                            <button class="button is-primary" id="bot_search">Get Bot Info</button>
                        </div>
                    </div>
                    <div class="is-hidden my-3" id="retrieve-info">
                        <div class="columns">
                            <div class="column is-one-third">
                                <span class="is-size-6 has-text-weight-semibold">Bot Token </span>
                            </div>
                            <div class="column">
                                <span id="bot-token"></span>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column is-one-third">
                                <span class="is-size-6 has-text-weight-semibold">Bot Name </span>
                            </div>
                            <div class="column">
                                <span id="bot-name"></span>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column is-one-third">
                                <span class="is-size-6 has-text-weight-semibold">Bot Username </span>
                            </div>
                            <div class="column">
                                <span id="bot-username"></span>
                            </div>
                        </div>
                        <form action="" method="POST">
                            @csrf
                            <input type="hidden" id="token" name="token">
                            <input type="hidden" id="name" name="name">
                            <input type="hidden" id="username" name="username">
                            <button class="button is-primary is-fullwidth">Add Telegram Bot</button>
                        </form>
                    </div>
                </div>
            </div>
            <button class="modal-close is-large" aria-label="close"></button>
        </div>
    </div>
@endsection

@section('script')
    @parent
    <script>
        $(document).ready(function() {
            $('#telegram-bot').select2({
                placeholder: "Select your telegram bot",
                // allowClear
            });
            $('#telegram-account').select2({
                placeholder: "Select your telegram account",
                // allowClear
            });
            $('#telegram-bot').on('change', function() {
                $('#search').submit();
            });
            $('#telegram-account').on('change', function() {
                $('#search').submit();
            });
            $('.select2-container').addClass('button');
            $('.select2-container').addClass('has-text-left');
            $('.select2-selection').addClass('border-0');

        });

    </script>
@endsection
