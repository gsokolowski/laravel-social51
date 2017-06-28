@extends('templates.default')

@section('content')
    <h3>You search for "{{ Request::input('query') }}"</h3> <!-- query comes from navigation.blade.php from search form -->

    @if (! count($users) )

        <p> No users found, sorry.</p>

    @else
        <div class="row">
            <div class="col-lg-12">
                @foreach($users as $user)
                    @include('user/partials/userblock')
                @endforeach
            </div>
        </div>
    @endif

@stop
