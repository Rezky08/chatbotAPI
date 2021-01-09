@extends('template.auth_template')

@section('main')
    <div class="box">
        <form action="" method="POST">
            @csrf
            @method($method)
            <div class="field">
                <label class="label">Label Name</label>
                <div class="control has-icons-left">
                    <input type="text" class="input" name="label_name" @isset($label)
                    value="{{ old('label_name', $label->label_name) }}" @else value="{{ old('label_name') }}" @endisset>
                <span class="icon is-left has-text-info">
                    <i class="fa fa-tag"></i>
                </span>
            </div>
            @error('label_name')
                <span class="help is-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="control has-text-right">
            @if ($method == 'PUT')
                <button class="button is-primary">Update Label</button>
            @else
                <button class="button is-primary">Add Label</button>
            @endif
        </div>
    </form>
</div>
@endsection
