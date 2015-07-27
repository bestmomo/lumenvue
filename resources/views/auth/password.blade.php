@extends('auth/template')

@section('content')
                <h2 class="text-center">Reset Password</h2>
                @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
                @endif
                <form role="form" method="POST" action="{{ url('password/email') }}">
                    <input type="hidden" name="_token" value="{{ session()->getToken() }}">
                    <div class="row">

                        <div class="form-group col-lg-6 {{ $errors->has('email')? 'has-error' : '' }}">
                            <input class="form-control" placeholder="E-Mail Address" name="email" type="email" value="{{ old('email') }}" required>
                            {!! $errors->first('email', '<small class="help-block">:message</small>') !!}
                        </div>
                        
                        <div class="form-group col-lg-6 text-center">
                            <input class="btn btn-default" type="submit" value="Send Password Reset Link">
                        </div> 
                        
                    </div>
                </form>                        
@stop