@extends('layouts.admin.master')
@section('title', 'Edit Profile')
@section("styles")
  <style type="text/css">
    .suggestive {
      font-weight: bold;
      font-size: 28px !important;
      text-shadow: 2px 8px 6px rgba(0, 0, 0, 0.2), 0px -5px 35px rgba(255, 255, 255, 0.3);
      margin: 0px 10px 20px 10px;
      text-align: left;
    }
    .error {
      text-align: left;
      margin: 30px 10px 20px 10px;
      font-size: 14 !important;
    }
	body {
	  overflow-x: hidden;
	}
  </style>
@endsection

@section('content')
	<div class="container">
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
		@if (count($errors) > 0)
		    <div class="alert alert-danger">
		        <ul>
		            @foreach ($errors->all() as $error)
		                <li>{{ $error }}</li>
		            @endforeach
		        </ul>
		    </div>
		@endif
		<div class="row">
			<p class="suggestive"><span class="glyphicon glyphicon-user"></span> Hi {{strstr($userDetails->name,' ', true)}}, you can edit your profile here</p>
		</div>
		<div class="row">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    <h3 class="panel-title">Update your Profile</h3>
			  </div>
			  <div class="panel-body">
			  	<div class="row">
			  		<div class = "col-md-6">
			  			<form method="POST" action="{{ route('adminUpdateAccount') }}">
					        <input type="hidden" name="_token" value="{{ csrf_token() }}">
					        <div class="form-group input-group">
					            <span class="input-group-addon" id="sizing-addon1">Name <i class="glyphicon glyphicon-user"></i></span>
					            <input type="text" class="form-control" required="" placeholder="Full Name" id="name" name="name" value="{!!$userDetails->name!!}">
					        </div>
					 
					        <div class="form-group input-group">
					            <span class="input-group-addon" id="sizing-addon1">Email <i class="glyphicon glyphicon-envelope"></i></span>
					            <input type="email" class="form-control" id="email" name="email" required="" placeholder="Email Id" aria-describedby="sizing-addon1" value="{{$userDetails->email}}">
					        </div>

					        <div class="form-group input-group">
								<input name="gender" type="radio" id="test1" value="1" @if(old('gender') == 1 || $userDetails->gender==1) checked @endif/>
								<label for="test1">&nbsp;Male</label>&nbsp;&nbsp;
								<input name="gender" type="radio" id="test2" value="2"@if(old('gender') == 2 || $userDetails->gender==2) checked @endif/>
								<label for="test2">&nbsp;Female</label>
							</div>

					        <div class="form-group input-group">
					            <select class="form-control" name="secques" id="secques">
									<option value="0" disabled selected>Update Security Question</option>
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
					            <input type="text" class="form-control" placeholder="Your answer for above" id="secans" name="secans" value="{!!old('secans')!!}">
					        </div>

					        <div class="form-group input-group">
					            <span class="input-group-addon" id="sizing-addon1">Old Password <i class="glyphicon glyphicon-lock"></i></span>
					            <input type="password" class="form-control" id="oldpassword" name="oldpassword" required="" placeholder="Enter your old password to save new password" aria-describedby="sizing-addon1" value="{!!old('oldpassword')!!}">
					        </div>
					 
					        <div class="form-group input-group">
					            <span class="input-group-addon" id="sizing-addon1">New Password <i class="glyphicon glyphicon-lock"></i></span>
					            <input type="password" class="form-control" id="password" name="password" placeholder="Should be at least 6 characters long" aria-describedby="sizing-addon1" value="">
					        </div>

					        <div class="form-group input-group">
					            <span class="input-group-addon" id="sizing-addon1">Confirm New Password <i class="glyphicon glyphicon-lock"></i></span>
					            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" aria-describedby="sizing-addon1" value="">
					        </div>
					 
					        <div class="form-group">
					            <button style="cursor:pointer"  class="btn btn-info pull-left" onclick="goBack()">Go back</button>
					            <button style="cursor:pointer" type="submit" class="btn btn-success pull-right">Save</button>
					        </div>
					    </form>
			  		</div>
			  	</div>
			  </div>
			</div>
			
		</div>
	    
	</div>

@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready( function() {
		$('#editprofile').addClass('active');
	});
	function goBack() {
	    window.history.back();
	}
</script>
@endsection