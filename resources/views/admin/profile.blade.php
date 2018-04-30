@extends('layouts.admin.master')
@section('title', 'User Profile')
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
			<p class="suggestive"><span class="glyphicon glyphicon-user"></span> Hi {{strstr($userDetails->name,' ', true)}}, you can view your profile here</p>
		</div>
		<div class="row">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    <h3 class="panel-title">View your Profile</h3>
			  </div>
			  <div class="panel-body">
			  	<div class="row">
			  		<div class = "col-md-6">
			  			<ul class="list-group">
						  <li class="list-group-item"><b>Name:</b> {{$userDetails->name}}</li>
						  <li class="list-group-item"><b>Email:</b> {{$userDetails->email}}</li>
						  <li class="list-group-item"><b>Gender:</b> @if($userDetails->gender==1) Male @else Female @endif</li>
						  <li class="list-group-item"><b>Role:</b> @if($userDetails->role==1) Normal User @elseif($userDetails->role==2) Admin @endif</li>
						  <li class="list-group-item"><b>Account created at </b>{{$userDetails->created_at}}</li>
						  <li class="list-group-item"><b>Account last udpated at </b>{{$userDetails->updated_at}}</li>
						</ul>
			  		</div>
			  		<div class = "col-md-2">
			  			<form action="{{route('adminEditProfile')}}" method="POST">
						    <input type="hidden" name="_token" value="{{ csrf_token() }}">
			  				<button type="submit" class="btn btn-primary">Update Profile</button>
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
		$('#profile').addClass('active');
	});
</script>
@endsection