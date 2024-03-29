@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card" style="margin-top: 150px;">
                <!--<div class="card-header"><h4 class="float-left">{{ $json->$hl->general->login_title }}</h4>
                <a href="{{route('register')}}" class="card-header-link float-right">{{$json->$hl->general->register}} &raquo;</a></div>-->

                <div class="card-body">
                    <h3 class="col-md-10 offset-md-1 text-center text-uppercase mb-3"><a href="#">SwiftBill</a></h3>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <!--<label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>-->

                            <div class="col-md-10 offset-md-1">
                                <div class="input-group">
                                    <div class="input-group-pretend">
                                        <div class="input-group-text"><i class="fas fa-envelope"></i></div>
                                    </div>

                                    <input id="email" type="email" placeholder="E-Mail" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <!--<label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>-->

                            <div class="col-md-10 offset-md-1">
                                <div class="input-group">
                                    <div class="input-group-pretend">
                                        <div class="input-group-text"><i class="fas fa-lock"></i></div>
                                    </div>

                                    <input id="password" type="password" placeholder="{{$json->$hl->general->password}}" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-10 offset-md-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ $json->$hl->general->remember }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-10 offset-md-1">
                                <button type="submit" class="btn btn-primary bg-main hover-main">
                                    {{ $json->$hl->general->login }}
                                </button>

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ $json->$hl->general->forgot_password }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
