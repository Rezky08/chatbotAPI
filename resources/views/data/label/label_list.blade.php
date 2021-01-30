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
        <div class="column is-one-third">
            <select name="searchbox" class="select2" style="width: 100%">
                <option value=""></option>
                @foreach ($apps as $key => $item)
                    <option value="{{ $item->id }}" @if ($app)
                        @if ($item->id==$app->id)
                        selected @endif
                @endif
                >{{ $item->client->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="column is-offset-one-third is-one-third has-text-right">
            @if ($app)
                <a class="button is-primary" href="{{ url(URL::current() . '/add', []) }}">
                    <span class="icon">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span>
                        Add Label
                    </span>
                </a>
            @endif
        </div>
    </div>


    {{-- table --}}
    <div class="box">
        <div class="table-container">
            <table class="table is-fullwidth">
                <thead>
                    <tr>
                        <th width='5%'>No</th>
                        <th width='40%'>Application Name</th>
                        <th width='50%'>Label Name</th>
                        <th width='5%'></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($labels as $key => $label)
                        <tr>
                            <td>{{ $number++ }}</td>
                            <td>{{ $label->application->client->name }}</td>
                            <td>{{ $label->label_name }}</td>
                            <td>
                                <div class="columns is-vcentered">
                                    <div class="column">
                                        <form action="{{ url('label/' . $label->application->id . '/' . $label->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="button border-0 has-tooltip-left"
                                                data-tooltip="Delete Label">
                                                <span class="icon is-small has-text-danger"><i
                                                        class="fa fa-trash"></i></span>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="column">
                                        <a href="{{ url(URL::current() . '/' . $label->id . '/edit') }}">
                                            <span class="icon has-text-info has-tooltip-left" data-tooltip="Edit Label">
                                                <i class="fas fa-edit"></i>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($labels)
            <x-pagination :paginator="$labels"></x-pagination>
        @endif
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
                window.location = base + "/label/" + $(this).val();
                console.log($(this).val());
            });
            $("#label_file").on('change', function() {
                let filename = getFileName($('#label_file').val());
                $('.file-name').html(filename);
            });

        });

    </script>
@endsection
