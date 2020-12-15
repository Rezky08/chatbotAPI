@extends('template.template')

@section('title',$title)

@section('head')
    @parent
    <style>
        .bg-chat{
            /* content: ""; */
            height: 100%;
            width: 100%;
            background: url("img/bglogin.png");
            opacity: 0.15;
            position: absolute;
            /* z-index: -11; */
        }
    </style>
@endsection

@section('content')
<div class="hero is-fullheight">
    <div class="hero-body has-background-info overflow-hidden">
        <div class="bg-chat"></div>
        <div class="container is-fullhd">
            <div class="columns is-vcentered">
                <div class="column is-one-third has-text-centered">
                    <figure class="is-inline-block">
                        <img src="{{ asset('img/chatboticon.svg') }}" alt="Chat Bot Icon" class="image is-128x128">
                    </figure>
                    <div class="block has-text-centered">
                        <span class="is-size-2 has-text-rubik has-text-white">CHAT BOT</span>
                    </div>
                </div>
                <div class="column">
                    <div class="container px-5">
                        <div class="box pa-5">
                            <span class="is-size-4 has-text-weight-semibold">Login</span>
                            <form action="" method="post" class="my-5">
                                @csrf
                                <div class="field">
                                    <div class="control has-icons-left">
                                        <input type="email" name="email" class="input is-medium" placeholder="Enter your email" value="{{old('email')}}">
                                        <span class="icon is-small is-left has-text-info">
                                            <i class="fa fa-envelope"></i>
                                        </span>
                                    </div>
                                    @error('email')
                                        <p class="help is-danger">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="field">
                                    <div class="control has-icons-left">
                                        <input type="password" name="password" class="input is-medium" placeholder="Enter your password">
                                        <span class="icon is-small is-left has-text-info">
                                            <i class="fa fa-key"></i>
                                        </span>
                                    </div>
                                    @error('password')
                                        <p class="help is-danger">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="columns">
                                    <div class="column">
                                        <a href="{{ url('register') }}" class="has-text-grey">doesn't have an account?</a>
                                    </div>
                                    <div class="column has-text-right">
                                        <a href="/" class="has-text-info">Forgot your password?</a>
                                    </div>
                                </div>
                                <div class="control">
                                    <button class="button is-info is-fullwidth is-medium">Login</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
