@extends('layouts.questions.master')
@section('title', 'Task Viewer')
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
			  			<form method="POST" action="{{ route('createFeatures') }}">
					        <input type="hidden" name="_token" value="{{ csrf_token() }}">
					        <div class="form-group input-group">
					            <span class="input-group-addon" id="sizing-addon1">Database <i class="glyphicon glyphicon-user"></i></span>
					            <input type="text" class="form-control"  placeholder="Database Name" id="database" name="database" value="{!!$userDetails->name!!}">
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
		$('#tv').addClass('active');
	});
	function goBack() {
		alert(1);
	    window.history.back();
	}
</script>
@endsection