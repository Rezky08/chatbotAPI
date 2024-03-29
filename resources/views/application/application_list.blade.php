@extends('template.auth_template')
@section('main')
    <div class="columns">
        <div class="column is-offset-half has-text-right">
            <a href="{{ url('/application/add') }}" class="button is-primary">
                <span class="icon"><i class="fa fa-plus"></i></span><span>Add Application</span>
            </a>
        </div>
    </div>

    {{-- table --}}
    <div class="box">
        <div class="table-container">
            <table class="table is-fullwidth">
                <thead>
                    <tr>
                        <th width="10%">No</th>
                        <th width="25%">Application Name</th>
                        <th width="15%">Secret Key</th>
                        <th width="25%">Redirect</th>
                        <th width="20%">Created</th>
                        <th width="5%"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($apps as $key => $app)
                        <tr>
                            <td>{{ $number++ }}</td>
                            <td>{{ $app->client->name }}</td>
                            <td><button class="button modal-button is-small is-info"
                                    secret-key="{{ $app->client->secret }}" data-target="modal"> Show </button></td>
                            <td>{{ $app->client->redirect }}</td>
                            <td>{{ $app->created_at }}</td>
                            <td>
                                <div class="columns is-centered">
                                    <div class="column">
                                        <form action="{{ url('application/' . $app->id . '/train') }}" method="post">
                                            @csrf
                                            <button type="submit" class="button border-0" data-tooltip="Train Application">
                                                <span class="icon is-small has-text-success">
                                                    <i class="fas fa-feather-alt"></i>
                                                </span>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="column">
                                        <form action="{{ url('application/' . $app->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="button border-0" data-tooltip="Revoke Key">
                                                <span class="icon is-small has-text-danger"><i
                                                        class="fa fa-trash"></i></span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($apps)
            <x-pagination :paginator="$apps"></x-pagination>
        @endif
    </div>

    {{-- modal --}}
    <div class="container is-clipped">
        <div class="modal" id="modal">
            <div class="modal-background">
            </div>
            <div class="modal-content is-clipped">
                <div class="box">
                    <span class="is-size-4 has-text-rubik has-text-weight-semibold">
                        Secret Key
                    </span>
                    <div class="columns is-vcentered">
                        <div class="column">
                            <span id="secret-key"></span>
                        </div>
                        <div class="column has-text-right">
                            <button class="button copy has-tooltip-left" data-tooltip="copy secret key"><span
                                    class="icon"><i class="fas fa-copy"></i></span></button>
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
        document.addEventListener('DOMContentLoaded', () => {
            let modal_toggler = document.querySelectorAll('.modal-button');
            let modal_close = document.querySelector('.modal-close');
            let modal_background = document.querySelector('.modal-close');
            document.querySelector('.copy').addEventListener('click', () => {
                let secret_key = document.getElementById('secret-key').innerText
                // Create new element
                var el = document.createElement('textarea');
                // Set value (string to be copied)
                el.value = secret_key;
                // Set non-editable to avoid focus and move outside of view
                el.setAttribute('readonly', '');
                el.style = {
                    position: 'absolute',
                    left: '-9999px'
                };
                document.body.appendChild(el);
                // Select text inside element
                el.select();
                // Copy text to clipboard
                document.execCommand('copy');
                // Remove temporary element
                document.body.removeChild(el);
            });
            modal_toggler.forEach((el) => {
                console.log(el);
                el.addEventListener('click', ($modal) => {
                    let modal_target = el.getAttribute('data-target');
                    let secret_key = el.getAttribute('secret-key');
                    document.getElementById('secret-key').innerHTML = secret_key;
                    document.querySelector('#' + modal_target).classList.add('is-active');
                });

            })
            modal_close.addEventListener('click', () => {
                modal_close.parentNode.classList.remove('is-active')
            });
        });

    </script>
@endsection
