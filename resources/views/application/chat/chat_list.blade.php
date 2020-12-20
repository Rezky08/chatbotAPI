@extends('template.auth_template')

@section('head')
    @parent
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endsection

@section('main')
    <div class="columns">
        <div class="column is-two-thirds">
            <select name="searchbox" class="select2" style="width: 100%">
                <option value=""></option>
                @foreach ($apps as $key => $item)
                    <option value="{{ $item->id }}" @if ($app)
                        @if ($item->id == $app->id)
                            selected
                        @endif
                @endif
                >{{ $item->client->name }}</option>
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

@endsection

@section('script')
    @parent
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select your application",
                // allowClear
            });
            $('.select2-container').addClass('button');
            $('.select2-container').addClass('has-text-left');
            $('.select2-selection').addClass('border-0');
            $('select.select2').on('change', function() {
                let base = "{{ url('') }}";
                window.location = base + "/application/chat/" + $(this).val();
                console.log($(this).val());
            });
        });

    </script>
@endsection
