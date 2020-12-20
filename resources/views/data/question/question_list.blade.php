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
                        @if ($item->id == $app->id)
                            selected
                        @endif
                @endif
                >{{ $item->client->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="column is-offset-one-third is-one-third has-text-right">
            @if ($app)
                <div class="dropdown is-hoverable is-right">
                    <div class="dropdown-trigger">
                        <button class="button is-primary">
                            <span class="icon">
                                <i class="fa fa-plus"></i>
                            </span>
                            <span>
                                Add Question
                            </span>
                        </button>
                    </div>
                    <div class="dropdown-menu">
                        <div class="dropdown-content">
                            <a class="modal-button dropdown-item" data-target="modal">
                                <span>Import File</span>
                                <span class="icon has-text-primary"><i class="fa fa-file"></i></span>
                            </a>
                            <a href="{{ url(URL::current() . '/add') }}" class="dropdown-item">
                                <span>Add Some Question</span>
                                <span class="icon has-text-primary"><i class="fa fa-question" aria-hidden="true"></i></span>
                            </a>
                        </div>
                    </div>
                </div>
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
                        <th width='20%'>Application Name</th>
                        <th width='60%'>Text</th>
                        <th width='10%'>Label</th>
                        <th width='5%'></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($questions as $key => $question)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $question->application->client->name }}</td>
                            <td>{{ $question->text }}</td>
                            <td>{{ $question->label->label_name }}</td>
                            <td>
                                <div class="columns is-vcentered">
                                    <div class="column">
                                        <form
                                            action="{{ url('question/' . $question->application->id . '/' . $question->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="button border-0 has-tooltip-left"
                                                data-tooltip="Delete Question">
                                                <span class="icon is-small has-text-danger"><i
                                                        class="fa fa-trash"></i></span>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="column">
                                        <a href="{{ url(URL::current() . '/' . $question->id . '/edit') }}">
                                            <span class="icon has-text-info has-tooltip-left" data-tooltip="Edit Question">
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
    </div>

    {{-- modal --}}
    <div class="container is-clipped">
        <div class="modal" id="modal">
            <div class="modal-background">
            </div>
            <div class="modal-content is-clipped">
                <div class="box">
                    <span class="is-size-4 has-text-rubik has-text-weight-semibold">
                        Import Question
                    </span>

                    <div class="block has-text-centered my-3">
                        <figure class="image">
                            <img src="{{ asset('img/fileeg.jpg') }}" alt="File Example">
                        </figure>
                        <span class="has-text-grey">Data Example Format</span>
                    </div>

                    <form action="{{ url(URL::current() . '/bulk', []) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="file has-name is-fullwidth">
                            <label class="file-label">
                                <input type="file" class="file-input" name="question_file" id="question_file">
                                <span class="file-cta">
                                    <span class="file-icon">
                                        <i class="fa fa-upload"></i>
                                    </span>
                                    <span class="file-label">Choose a question file...</span>
                                </span>
                                <span class="file-name">
                                </span>
                            </label>
                        </div>
                        <div class="columns">
                            <div class="column is-half">
                                @error('question_file')
                                    <span class="help is-danger is-size-6">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="column is-half">
                                <span class="has-text-grey is-size-7">Accept files csv,xls,xlsx </span>
                            </div>
                        </div>
                        <div class="field my-3 block">
                            <button class="button is-primary is-fullwidth">Import</button>
                        </div>
                    </form>

                </div>
            </div>
            <button class="modal-close is-large" aria-label="close"></button>
        </div>
    </div>
@endsection


@section('script')
    @parent
    <script>
        function getFileName(fullPath) {
            var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
            var filename = fullPath.substring(startIndex);
            if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                filename = filename.substring(1);
            }
            return filename
        }
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
                window.location = base + "/question/" + $(this).val();
                console.log($(this).val());
            });
            $("#question_file").on('change', function() {
                let filename = getFileName($('#question_file').val());
                $('.file-name').html(filename);
            });

        });

        document.addEventListener('DOMContentLoaded', () => {
            let modal_toggler = document.querySelectorAll('.modal-button');
            let modal_close = document.querySelector('.modal-close');
            let modal_background = document.querySelector('.modal-close');
            modal_toggler.forEach((el) => {
                el.addEventListener('click', ($modal) => {
                    let modal_target = el.getAttribute('data-target');
                    document.querySelector('#' + modal_target).classList.add('is-active');
                });

            })
            modal_close.addEventListener('click', () => {
                modal_close.parentNode.classList.remove('is-active')
            });
        });

    </script>
@endsection
