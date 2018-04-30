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
			<p class="suggestive">Hi {{strstr(Auth::user()->email,'@', true)}}, you can manage your profile here</p>
		</div>
		<div class="row">
	
			
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