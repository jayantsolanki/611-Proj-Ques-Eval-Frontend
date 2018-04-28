@extends('layouts.registration.login')
@section('styles')
@stop

@section('content')
<div class="col-md-4 col-sm-5 col-md-offset-4">
	@if (session('success'))
	<div class="row">
		<div class="alert alert-success text-center">
			<p>{{session('success')}}</p>
		</div>
	</div>
	@endif

	@if (session('error'))
	<div class="alert alert-danger">
		{{session('error')}}
	</div>
	@endif
	<form method="POST" action="{{ route('user_login') }}">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<p class="suggestive">Login to Dashboard</p>
		<div class="row">
			<div class="col-md-6 text-left" style="padding-left:25px;">
				<b class="text text-uppercase">Sign in</b>
			</div>
			<div class="col-md-6 text-right" style="padding-right:30px;">
				or<a href="{{ route('createAccountPage') }}"><span class="text text-primary"> create new Account</span></a>
			</div>
		</div>
		<div class="input-group input-group-lg">
			<span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-user"></i></span>
			<input type="email" name="username" value="{{ old('username') }}" class="form-control" required="" placeholder="Username" aria-describedby="sizing-addon1">
		</div>
		<div class="input-group input-group-lg">
			<span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-lock"></i></span>
			<input type="password" name="password" value="" class="form-control" required="" placeholder="Password" aria-describedby="sizing-addon1">
		</div>
		<div class="row">
			<div class="col-md-6 text-left" style="padding-left:25px;">
				<a href="{{ route('forgotpass') }}" class="text text-primary">Forgot your Password?</a>
			</div>
			<div class="col-md-6 text-right" style="padding-right:30px;">
				<button class="btn btn-primary" type="submit" name="submit">Login</button>
			</div>
		</div>
	</form>
	@if (count($errors) > 0)
	    <div class="alert alert-danger">
	        <ul>
	            @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </ul>
	    </div>
	@endif
</div>
@endsection

@section('scripts')
@endsection
