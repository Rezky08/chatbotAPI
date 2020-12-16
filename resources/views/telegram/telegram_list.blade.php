@extends('template.auth_template')
@section('main')
<div class="columns">
    <div class="column is-offset-half has-text-right">
        <button class="button is-primary modal-button" data-target="modal"><span class="icon"><i class="fa fa-plus"></i></span><span>Add Telegram Bot</span></button>
    </div>
</div>

{{-- table --}}
<div class="box">
    <div class="table-container">
        <table class="table is-fullwidth">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Bot Name</th>
                    <th>Username</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($telegrams as $key=>$telegram)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$telegram->name}}</td>
                        <td>{{$telegram->username}}</td>
                        <td>
                            <form action="{{ url('app-key/'.$app->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button border-0" data-tooltip="Revoke Key">
                                    <span class="icon is-small has-text-danger"><i class="fa fa-trash"></i></span>
                                </button>
                            </form>
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
                        <input type="hidden" id="token" name="token" >
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
        document.addEventListener('DOMContentLoaded',()=>{
            function getBotDetail() {
                let bot_token = document.getElementById('bot_token').value
                let requestOptions = {
                    method: 'GET',
                    redirect: 'follow'
                    };
                let telegramAPI = "https://api.telegram.org/bot";
                let urlTarget = telegramAPI+bot_token+"/getMe";

                // async
                (async ()=>{
                    const resp = await fetch(urlTarget,requestOptions).then((data)=>{
                        return data.json();
                    }).then((data)=>{
                        res = data['result'];
                        document.getElementById('bot-name').innerHTML = res['first_name'];
                        document.getElementById('bot-username').innerHTML = res['username'];
                        document.getElementById('bot-token').innerHTML = bot_token;
                        document.getElementById('token').value = bot_token;
                        document.getElementById('name').value = res['first_name'];
                        document.getElementById('username').value = res['username'];
                        document.getElementById('form-bot').classList.add('is-hidden');
                        document.getElementById('retrieve-info').classList.remove('is-hidden');
                    }).catch((resp)=>{
                        message = resp.message;
                        // console.log(resp.message);
                        document.getElementById('bot_error').innerHTML = message;
                        document.getElementById('bot_error').classList.remove('is-hidden');
                    });
                    document.getElementById('bot_search').classList.remove('is-loading');
                    return resp;
                })();
                document.getElementById('bot_search').classList.add('is-loading');
            }
            function resetBotDetail(){
                document.getElementById('bot_error').classList.add('is-hidden');
                document.getElementById('bot_search').classList.remove('is-loading');
                document.getElementById('bot-name').innerHTML = "";
                document.getElementById('bot-username').innerHTML = "";
                document.getElementById('bot-token').innerHTML = "";
                document.getElementById('bot_token').value = "";
                document.getElementById('token').value = "";
                document.getElementById('name').value = "";
                document.getElementById('username').value = "";
                document.getElementById('form-bot').classList.remove('is-hidden');
                document.getElementById('retrieve-info').classList.add('is-hidden');
            }
            document.getElementById('bot_search').addEventListener('click',()=>{
                getBotDetail();
            });
            let modal_toggler = document.querySelectorAll('.modal-button');
            let modal_close = document.querySelector('.modal-close');
            let modal_background = document.querySelector('.modal-close');
            modal_toggler.forEach((el)=>{
                el.addEventListener('click',($modal)=>{
                    let modal_target = el.getAttribute('data-target');
                    document.querySelector('#'+modal_target).classList.add('is-active');
                });

            })
            modal_close.addEventListener('click',()=>{
                modal_close.parentNode.classList.remove('is-active');
                resetBotDetail();
            });
        });
    </script>
@endsection
