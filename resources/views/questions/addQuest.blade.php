@extends('layouts.questions.master')
@section('title', 'Question Editor')
@section('styles')
@stop

@section('content')
<h1>This is Question Editor</h1>
@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready( function() {
		$('#qe').addClass('active');
	});
</script>
@endsection