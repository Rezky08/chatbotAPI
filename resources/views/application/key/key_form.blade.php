@extends('template.auth_template')
@section('main')
    <div class="box">
        <form action="" method="POST">
            @csrf
            <div class="columns">
                <div class="column">
                    <div class="field">
                        <div class="control">
                            <label class="label">Application Name</label>
                            <input type="text" name="app_name" class="input" value="{{ old('app_name') }}">
                        </div>
                        @error('app_name')
                            <span class="help is-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="column">
                    <div class="field">
                        <div class="control">
                            <label class="label">Redirect</label>
                            <input type="text" name="redirect" class="input" value="{{ old('redirect') }}">
                        </div>
                        @error('redirect')
                            <span class="help is-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="control has-text-right">
                <button class="button is-primary">Add Application</button>
            </div>
        </form>
    </div>
@endsection
