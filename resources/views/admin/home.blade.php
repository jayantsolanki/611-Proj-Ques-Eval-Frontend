@extends('layouts.admin.master')
@section('title', 'Dashboard')
@section('styles')
@stop

@section('content')
<h1>This is User Dashboard Page</h1>
@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready( function() {
		$('#dashboard').addClass('active');
	});
</script>
@endsection