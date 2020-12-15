@include('template.header')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('img/chatboticon.svg') }}">
    <style>
        .notification.is-danger,.notification.is-success{
            position: absolute;
            z-index: 99;
        }
    </style>
    @section('head')

    @show
    <title>@yield('title')</title>
</head>
<body>
    @if(Session::has('error'))
    <div class="notification is-danger">
        <button class="delete"></button>
        {{Session::get('error')}}
    </div>
    @endif
    @if(Session::has('success'))
    <div class="notification is-success">
        <button class="delete"></button>
        {{Session::get('success')}}
    </div>
    @endif
@section('content')

@show
@section('script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
  (document.querySelectorAll('.notification .delete') || []).forEach(($delete) => {
    var $notification = $delete.parentNode;

    $delete.addEventListener('click', () => {
      $notification.parentNode.removeChild($notification);
    });
  });
});
</script>
@show
</body>
</html>
