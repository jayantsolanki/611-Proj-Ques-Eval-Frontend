@extends('layouts.questions.master')
@section('title', 'Task Viewer')
@section('styles')
@stop

@section('content')
<h1>This is Task Viewer Page</h1>
@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready( function() {
		$('#tv').addClass('active');
	});
</script>
@endsection