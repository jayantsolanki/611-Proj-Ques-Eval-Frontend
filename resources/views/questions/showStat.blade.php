@extends('layouts.questions.master')
@section('title', 'Statistics')
@section('styles')
@stop

@section('content')
<h1>This is Statistics Page</h1>
@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready( function() {
		$('#st').addClass('active');
	});
</script>
@endsection