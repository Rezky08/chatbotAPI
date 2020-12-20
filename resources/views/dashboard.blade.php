@extends('template.auth_template')
@section('main')
    <div class="columns">
        <div class="column">
            <div class="box has-text-centered ">
                <span class="is-size-5 has-text-weight-semibold">{{ $telegram_count }}</span>
                <div class="block has-text-rubik has-text-grey">
                    <span class="is-size-6">Telegram</span>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="box has-text-centered ">
                <span class="is-size-5 has-text-weight-semibold">{{ $account_count }}</span>
                <div class="block has-text-rubik has-text-grey">
                    <span class="is-size-6">Account</span>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="box has-text-centered ">
                <span class="is-size-5 has-text-weight-semibold">{{ $chat_count }}</span>
                <div class="block has-text-rubik has-text-grey">
                    <span class="is-size-6">Chat</span>
                </div>
            </div>
        </div>

    </div>
    <div class="columns">
        <div class="column">
            <div class="box has-text-centered ">
                <span class="is-size-5 has-text-weight-semibold">{{ $question_count }}</span>
                <div class="block has-text-rubik has-text-grey">
                    <span class="is-size-6">Question</span>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="box has-text-centered ">
                <span class="is-size-5 has-text-weight-semibold">{{ $answer_count }}</span>
                <div class="block has-text-rubik has-text-grey">
                    <span class="is-size-6">Answer</span>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="box has-text-centered ">
                <span class="is-size-5 has-text-weight-semibold">{{ $label_count }}</span>
                <div class="block has-text-rubik has-text-grey">
                    <span class="is-size-6">Label</span>
                </div>
            </div>
        </div>

    </div>
@endsection
