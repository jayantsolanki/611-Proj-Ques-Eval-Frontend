@extends('layouts.admin.master')
@section('title', 'User Management')
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
			<p class="suggestive">You can manage Users' profiles here</p>
		</div>
		<div class="row">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    <h3 class="panel-title">Choose user profile to edit</h3>
			  </div>
			  <div class="panel-body">
			  	<div class="row">
			  		<div class = "col-md-12">
			  			<form method="POST" action="{{ route('userManage') }}">
					        <input type="hidden" name="_token" value="{{ csrf_token() }}">
				  			<table class="table">
							  <thead>
							    <tr>
							      <th scope="col">UserId</th>
							      <th scope="col">Name</th>
							      <th scope="col">Email</th>
							      <!-- <th scope="col">Gender</th> -->
							      <th scope="col">Role</th>
							      <th scope="col">Access</th>
							      <th scope="col"><span class="text text-warning">Select User to edit</span></th>
							    </tr>
							  </thead>
							  <tbody>
							  	@foreach ($userDetails as $userDetail)
							  	<tr>
									<th scope="row">
										{{$userDetail->id}}
									</th>
									<td>
										@if($userDetail->active==0) <span class="badge badge-danger">new</span> @endif {{$userDetail->name}}
									</td>
									<td>
										{{$userDetail->email}}
									</td>
									<!-- <td>
										@if($userDetail->gender==1) Male @else Female @endif
									</td> -->
									<td>
										<select class="form-control" name="userRole{{$userDetail->id}}" id="role">
											<option value="0" disabled selected>Choose role</option>
											<option value="1" @if($userDetail->role==1) selected @endif> Normal User</option>
											<option value="2" @if($userDetail->role==2) selected @endif> Admin</option>
										</select>
									</td>
									<td>
										<select class="form-control" name="userActive{{$userDetail->id}}" id="active">
											<option value="" disabled selected>Choose role</option>
											<option value="0" @if($userDetail->active==0) selected @endif> Inactive</option>
											<option value="1" @if($userDetail->active==1) selected @endif> Active</option>
											<option value="2" @if($userDetail->active==2) selected @endif> Blacklisted</option>
										</select>
									</td>
									<td class="text text-center" for="test{{$userDetail->id}}">
										<input name="selectUser" type="radio" id="test{{$userDetail->id}}" value="{{$userDetail->id}}"/>
									</td>
							    </tr>
							  	@endforeach
							  </tbody>
							</table>
					 
					        <div class="form-group">
					            <button style="cursor:pointer" type="submit" class="btn btn-success pull-right">Save changes</button>
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
		$('#usermang').addClass('active');
	});
</script>
@endsection