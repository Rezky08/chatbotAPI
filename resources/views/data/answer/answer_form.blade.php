@extends('template.auth_template')

@section('head')
    @parent
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endsection


@section('main')
    <div class="box">
        <form action="" method="POST">
            @csrf
            @method($method)
            <div class="field">
                <label class="label">Answer Label</label>
                <div class="control">
                    <select name="label_id" class="select2" style="width: 100%">
                        <option value=""></option>
                        @foreach ($labels as $key => $label)
                            <option value="{{ $label->id }}" @if ($label->id == old('label_id'))
                                selected
                        @endif
                        @isset($answer)
                            @if ($label->id == $answer->label_id)
                                selected
                            @endif
                        @endisset
                        > {{ $label->label_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="field">
                <label class="label">Answer Text</label>
                <div class="control">
                    <textarea name="text" class="textarea"
                        style="resize: none">@isset($answer){{ $answer->text }}@endisset</textarea>
                </div>
            </div>
            <div class="control has-text-right">
                @if ($method == 'PUT')
                    <button class="button is-primary">Update Answer</button>
                @else
                    <button class="button is-primary">Add Answer</button>
                @endif
            </div>
        </form>
    </div>
@endsection

@section('script')
    @parent
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select label ...",
                // allowClear
            });
            $('.select2-container').addClass('button');
            $('.select2-container').addClass('has-text-left');
            $('.select2-selection').addClass('border-0');


        });

    </script>
@endsection
