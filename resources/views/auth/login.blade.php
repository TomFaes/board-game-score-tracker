@extends('layouts.app')

@section('content')
<div class="container">
    <br><br>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <a class="social btn btn-block btn-social btn-sm btn-google" href="http://localhost/boardgametracker/public/login/google"><i class="fab fa-google-plus-square"></i>Sign in with Google</a>
                                <a class="social btn btn-block btn-social btn-sm btn-microsoft" href="http://localhost/boardgametracker/public/login/microsoft"><i class="fab fa-windows"></i>Sign in with Microsoft</a>
                                <a class="social btn btn-block btn-social btn-sm btn-facebook" href="http://localhost/boardgametracker/public/login/facebook"><i class="fab fa-facebook-square"></i>Sign in with Facebook</a>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
