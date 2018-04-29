@extends('layouts.members.master')
@section('title', 'Edit Profile')
@section('styles')
@stop

@section('content')
<h1>This is User Edit Profile Page</h1>
@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready( function() {
		$('#editprofile').addClass('active');
	});
</script>
@endsection