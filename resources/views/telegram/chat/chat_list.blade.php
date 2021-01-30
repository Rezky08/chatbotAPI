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
    <div class="columns">
        <div class="column is-half">
            <select name="telegram_bot" id="telegram-bot" style="width: 100%">
                <option value=""></option>
                @foreach ($telegrams as $key => $item)
                    <option value="{{ $item->id }}" @if ($telegram)
                        @if ($item->id==$telegram->id)
                        selected @endif
                @endif
                >{{ $item->name }} - {{ $item->username }}</option>
                @endforeach
            </select>
        </div>
        <div class="column is-half">
            <select name="telegram_account" id="telegram-account" style="width: 100%">
                <option value=""></option>
                @foreach ($accounts as $key => $item)
                    <option value="{{ $item->id }}" @if ($account)
                        @if ($item->id==$account->id)
                        selected @endif
                @endif
                >{{ $item->name }} - {{ $item->username }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- table --}}
    <div class="box">
        <div class="table-container">
            <table class="table is-fullwidth">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>App Name</th>
                        <th>Text</th>
                        <th>replied</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($chats as $key => $chat)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td id='app-name-{{ $key }}'>{{ $chat->application->client->name }}</td>
                            <td>{{ $chat->text }}</td>
                            <td>
                                @if ($chat->replied)
                                    <span class="tag is-success">Yes</span>
                                @else
                                    <span class="tag is-danger">No</span>
                                @endif
                            </td>
                            <td>
                                <button class="button is-primary is-small modal-button" data-target="modal"
                                    chat-id='{{ $key }}'>
                                    <span>Detail</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($chats)
            <x-pagination :paginator="$chats"></x-pagination>

        @endif
    </div>

    {{-- modal --}}
    <div class="container is-clipped">
        <div class="modal" id="modal">
            <div class="modal-background">
            </div>
            <div class="modal-content is-clipped">
                <div class="box">
                    <span class="has-text-rubik is-size-4 has-text-weight-semibold">Chat Detail</span>
                    <div class="columns mt-3">
                        <div class="column">
                            <span class='has-text-weight-semibold'>From</span>
                        </div>
                        <div class="column">
                            <span id="detail-username"></span>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column">
                            <span class='has-text-weight-semibold'>Application Name</span>
                        </div>
                        <div class="column">
                            <span id="detail-app-name"></span>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column">
                            <span class='has-text-weight-semibold'>Text</span>
                        </div>
                        <div class="column">
                            <span id="detail-text"></span>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column">
                            <span class='has-text-weight-semibold'>Replied</span>
                        </div>
                        <div class="column">
                            <span id="detail-replied"></span>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column">
                            <span class='has-text-weight-semibold'>Response</span>
                        </div>
                        <div class="column">
                            <span id="detail-text-response"></span>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column">
                            <span class='has-text-weight-semibold'>Received Date</span>
                        </div>
                        <div class="column">
                            <span id="detail-receive-date"></span>
                        </div>
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
            let base_url = "{{ url('') }}";
            $('#telegram-bot').select2({
                placeholder: "Select your telegram bot",
                // allowClear
            });
            $('#telegram-account').select2({
                placeholder: "Select your telegram account",
                // allowClear
            });
            $('.select2-container').addClass('button');
            $('.select2-container').addClass('has-text-left');
            $('.select2-selection').addClass('border-0');
            $('#telegram-bot').on('change', function() {
                console.log(base_url);
                window.location = base_url + '/telegram/chat/' + $('#telegram-bot').val();
            });
            $('#telegram-account').on('change', function() {
                window.location = base_url + '/telegram/chat/' + $('#telegram-bot').val() + '/' + $(
                    '#telegram-account').val();
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            function changeChatDetail(id) {
                let chat = data['chats'][id];
                document.getElementById('detail-username').innerHTML = data['account']['username'];

                document.getElementById('detail-app-name').innerHTML = document.getElementById('app-name-' + id)
                    .innerText;

                document.getElementById('detail-text').innerHTML = chat['text']

                document.getElementById('detail-text-response').innerHTML = chat['text_response']

                replied_html = ''
                if (chat['replied']) {
                    replied_html += '<span class="tag is-success">Yes</span>'
                } else {
                    replied_html += '<span class="tag is-danger">No</span>'
                }
                document.getElementById('detail-replied').innerHTML = replied_html

                document.getElementById('detail-receive-date').innerHTML = chat['created_at']



            }
            let modal_toggler = document.querySelectorAll('.modal-button');
            let modal_close = document.querySelector('.modal-close');
            let modal_background = document.querySelector('.modal-close');
            modal_toggler.forEach((el) => {
                el.addEventListener('click', ($modal) => {
                    let modal_target = el.getAttribute('data-target');
                    let chat_id = el.getAttribute('chat-id');
                    changeChatDetail(chat_id);
                    document.querySelector('#' + modal_target).classList.add('is-active');
                });

            })
            modal_close.addEventListener('click', () => {
                modal_close.parentNode.classList.remove('is-active')
            });
        });

    </script>
@endsection
