@extends('layouts.registration.master')
@section('title', 'User Registration')
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
		<form method="POST" action="{{ route('createAccount') }}">
	        <input type="hidden" name="_token" value="{{ csrf_token() }}">
			<p class="suggestive">Create new Account</p>
			<div class="row">
			<div class="col-md-offset-6 col-md-6 text-right" style="padding-right:30px;">
				Already have an account?<a href="{{ route('login') }}"><span class="text text-primary"> Sign In </span></a>
			</div>
		</div>
	        <div class="form-group input-group">
	            <span class="input-group-addon" id="sizing-addon1">Name <i class="glyphicon glyphicon-user"></i></span>
	            <input type="text" class="form-control" required="" placeholder="Full Name" id="name" name="name" value="{!!old('name')!!}">
	        </div>
	 
	        <div class="form-group input-group">
	            <span class="input-group-addon" id="sizing-addon1">Email <i class="glyphicon glyphicon-envelope"></i></span>
	            <input type="email" class="form-control" id="email" name="email" required="" placeholder="Email Id" aria-describedby="sizing-addon1" value="{!!old('email')!!}">
	        </div>

	        <div class="form-group input-group">
				<input name="gender" type="radio" id="test1" value="1" @if(old('gender') == 1) checked @endif/>
				<label for="test1">&nbsp;Male</label>&nbsp;&nbsp;
				<input name="gender" type="radio" id="test2" value="2"@if(old('gender') == 2) checked @endif/>
				<label for="test2">&nbsp;Female</label>
			</div>

	        <div class="form-group input-group">
	            <select required="" class="form-control" name="secques" id="secques">
					<option value="" disabled selected>Choose Security Question</option>
					<option value="1" @if(old('secques') == 1) selected @endif>What was your childhood nickname?</option>
					<option value="2" @if(old('secques') == 2) selected @endif>What is your favorite movie?</option>
					<option value="3"@if(old('secques') == 3) selected @endif >Name of your birth city?</option>
					<option value="4" @if(old('secques') == 4) selected @endif>What school did you attend for sixth grade?</option>
					<option value="5" @if(old('secques') == 5) selected @endif>What was the last name of your third grade teacher?</option>
					<option value="6" @if(old('secques') == 6) selected @endif>What was the make and model of your first car?</option>
				</select>
	        </div>

	        <div class="form-group input-group">
	            <span class="input-group-addon" id="sizing-addon1">Answer <i class="glyphicon glyphicon-text-background"></i></span>
	            <input type="text" class="form-control" required="" placeholder="Your answer for above" id="secans" name="secans" value="{!!old('secans')!!}">
	        </div>
	 
	        <div class="form-group input-group">
	            <span class="input-group-addon" id="sizing-addon1">Password <i class="glyphicon glyphicon-lock"></i></span>
	            <input type="password" class="form-control" id="password" name="password" required="" placeholder="Should be at least 6 characters long" aria-describedby="sizing-addon1" value="{!!old('password')!!}">
	        </div>

	        <div class="form-group input-group">
	            <span class="input-group-addon" id="sizing-addon1">Confirm Password <i class="glyphicon glyphicon-lock"></i></span>
	            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required="" placeholder="Confirm Password" aria-describedby="sizing-addon1" value="{!!old('password')!!}">
	        </div>
	 
	        <div class="form-group">
	            <button style="cursor:pointer" type="submit" class="btn btn-primary">Submit</button>
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
