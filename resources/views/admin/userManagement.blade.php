@extends('layouts.admin.master')
@section('title', 'User Management')
@section('styles')
@stop

@section('content')
<h1>Provide admin rights to user here</h1>
@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready( function() {
		$('#usermang').addClass('active');
	});
</script>
@endsection