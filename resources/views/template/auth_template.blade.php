@extends('template.template')
@include('template.navbar')
@include('template.sidebar')

@section('title',$title)


@section('content')
    @section('navbar')
    @show
    <div class="container is-fluid">
        <div class="columns">
            <div class="column is-one-fifth">
                @section('sidebar')
                @show
            </div>
            <div class="column is-four-fifths">
                <div class="block my-5">
                    <span class="has-text-rubik is-size-3 has-text-weight-bold">{{$title}}</span>
                    <nav class="breadcrumb">
                        <ul>
                            <li><a href="{{ url('/') }}">{{$title}}</a></li>
                            <li><a href="#">test</a></li>
                            <li><a href="#">test</a></li>
                        </ul>
                    </nav>
                </div>
                @section('main')
                @show
            </div>
        </div>
    </div>
@endsection

@section('script')
    @parent
    <script>
    document.addEventListener('DOMContentLoaded', () => {

        // Get all "navbar-burger" elements
        const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

        // Check if there are any navbar burgers
        if ($navbarBurgers.length > 0) {

          // Add a click event on each of them
          $navbarBurgers.forEach( el => {
            el.addEventListener('click', () => {

              // Get the target from the "data-target" attribute
              const target = el.dataset.target;
              const $target = document.getElementById(target);

              // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
              el.classList.toggle('is-active');
              $target.classList.toggle('is-active');

            });
          });
        }

      });
    </script>
@endsection
