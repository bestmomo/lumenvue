@extends('auth/template')

@section('content')
                <h2 class="text-center">Reset Password</h2>
                <form role="form" method="POST" action="{{ url('password/reset') }}">
                    <input type="hidden" name="_token" value="{{ session()->getToken() }}">
                    <input type="hidden" name="token" value="{{ $token }}">
                    
                    <div class="row">

                        <div class="form-group col-lg-6 {{ $errors->has('email')? 'has-error' : '' }}">
                            <input class="form-control" placeholder="E-Mail Address" name="email" type="email" value="{{ old('email') }}" required>
                            {!! $errors->first('email', '<small class="help-block">:message</small>') !!}
                        </div>
                        
                    </div>
                    
                    <div class="row">
                        
                        <div class="form-group col-lg-6 {{ $errors->has('password')? 'has-error' : '' }}">
                            <input class="form-control" placeholder="Password" name="password" type="password" required>
                            {!! $errors->first('password', '<small class="help-block">:message</small>') !!}
                        </div>
                        
                        <div class="form-group col-lg-6">
                            <input class="form-control" placeholder="Confirm Password" name="password_confirmation" type="password" >
                        </div>

                        <div class="form-group col-lg-12 text-center">
                            <input class="btn btn-default" type="submit" value="Reset Password">
                        </div> 
                        
                    </div>
                </form>                        
@stop