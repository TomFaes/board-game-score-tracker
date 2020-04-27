@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-0 col-lg-1"></div>
        <div class="col-0 col-lg-10">
            @auth
                Old Version
                <index-page-old></index-page-old>
            @endauth
            <br>
        </div>
        <div class="col-0 col-lg-1"></div>
    </div>


@endsection
