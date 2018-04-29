@extends('layouts.admin.master')
@section('title', 'User Profile')
@section('styles')
@stop

@section('content')
<h1>This is User Profile Page</h1>
<a href = "{{route('adminEditProfile')}}">Edit Profile</a>
@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready( function() {
		$('#profile').addClass('active');
	});
</script>
@endsection