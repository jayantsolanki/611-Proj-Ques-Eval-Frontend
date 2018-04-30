@extends('layouts.questions.master')
@section('title', 'Question Viewer')
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
    .ques {
      margin: 0px 10px 20px 10px;
      text-align: right;
    }
    .corr {
      margin: 0px 0px 00px 15px;
    }
    .quesbody {
      margin: 0px 10px 20px 10px;
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
			<p class="suggestive">Browse Questions</p>
			@if($defaultyear==null)
			<p class="error label label-danger">No database found, please add question database</p>
			@else
			<form class ="form-inline" method="POST" action="{{ route('quesViewer') }}">
		        <input type="hidden" name="_token" value="{{ csrf_token() }}">
		        <input type="hidden" name="new" value="filter">
				<div class="row">
			        <div class="input-group col-md-2 corr">
			            <select required="" class="form-control" name="year" id="year">
			            	<option value="" disabled selected>Select year</option>
			            	@foreach ($years as $year)
			            		<option value="{{$year}}" @if($year == $defaultyear) selected @endif>{{$year}}</option>
			            	@endforeach
						</select>
			        </div>
			        <div class=" input-group col-md-3">
			            <select required="" class="form-control" name="category" id="category">
							<option value="" disabled selected>Select Category</option>
							<option value="1" @if($category == 1) selected @endif>Aptitude</option>
							<option value="2" @if($category == 2) selected @endif>Electricals</option>
							<option value="3"@if($category == 3) selected @endif >Programming</option>
							<option value="4" @if($category == 4) selected @endif>All</option>
						</select>
			        </div>
			        <div class=" input-group col-md-2">
			            <select required="" class="form-control" name="difficulty" id="difficulty">
							<option value="" disabled selected>Select Difficulty level</option>
							<option value="0" @if($difficulty == 0) selected @endif>Easy</option>
							<option value="1" @if($difficulty == 1) selected @endif>Medium</option>
							<option value="2"@if($difficulty == 2) selected @endif >Hard</option>
							<option value="3" @if($difficulty == 3) selected @endif>All</option>
						</select>
			        </div>
			        <div class=" input-group col-md-2">
			            <button style="cursor:pointer" type="submit" class="btn btn-danger">Change Filter</button>
			        </div>
			    </div> 
		            
		    </form>
		    <br/>
		    <form name="gotoQuest" class ="form-inline" method="POST" action="{{ route('quesViewer') }}">
		    	<div class="input-group col-md-offset-0">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="year" value="{{ $defaultyear }}">
					<input type="hidden" name="difficulty" value="{{ $difficulty }}">
					<input type="hidden" name="category" value="{{ $category }}">
					<input type="text" class="form-control" name="current" value="{{ $fetchQues->quid }}">
					<input type="hidden" name="new" value="goto">
				</div>
			  <div class=" input-group  col-md-2">
		            <button style="cursor:pointer" type="submit" class="btn btn-danger">Goto</button>
		        </div>
			</form>
		    @endif
		</div>
		<div class="row">
			<div>
				@if($fetchQues == null)
				<p class="label label-danger">No questions found</p>
				@else
				<div class="ques row">
					<div class=" pull-left">
						<form name="prevQuest" class ="form-inline" method="POST" action="{{ route('quesViewer') }}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input type="hidden" name="year" value="{{ $defaultyear }}">
							<input type="hidden" name="difficulty" value="{{ $difficulty }}">
							<input type="hidden" name="category" value="{{ $category }}">
							<input type="hidden" name="current" value="{{ $fetchQues->quid }}">
							<input type="hidden" name="new" value="previous">
						  <ul class="pagination justify-content-end">
						    <li class="page-item @if($previous == 0) disabled @endif">
						      <a class="page-link" href="@if($previous == 0) # @else javascript: submitprev() @endif" tabindex="-1">Previous</a>
						    </li>
						  </ul>
						</form>
					</div>
					<div class="pull-right">
						<form name="nextQuest" class ="form-inline" method="POST" action="{{ route('quesViewer') }}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input type="hidden" name="year" value="{{ $defaultyear }}">
							<input type="hidden" name="difficulty" value="{{ $difficulty }}">
							<input type="hidden" name="category" value="{{ $category }}">
							<input type="hidden" name="current" value="{{ $fetchQues->quid }}">
							<input type="hidden" name="new" value="next">
						  <ul class="pagination justify-content-end">
						    <li class="page-item @if($next == 0) disabled @endif">
						      <a class="page-link" href="@if($next == 0) # @else javascript: submitnext() @endif">Next</a>
						    </li>
						  </ul>
						</form>
					</div>
				</div>

				<div class="panel panel-default">
				  <div class="panel-heading">
				    <h3 class="panel-title">Question Id: <b class="text text-info">{{$fetchQues->quid}}</b>&nbsp;&nbsp;Category: <b class="text text-info">@if($fetchQues->category_id == 1) Aptitude @elseif($fetchQues->category_id == 2) Electricals @elseif($fetchQues->category_id == 3) Programming @endif</b>&nbsp;&nbsp;Difficulty Level: @if($fetchQues->pre_tag == 0) <b class="text text-success">Easy</b> @elseif($fetchQues->pre_tag == 1)<b class="text text-warning"> Medium </b>@elseif($fetchQues->pre_tag == 2)<b class="text text-danger"> Hard </b>@endif&nbsp;&nbsp Year: <b class="text text-warning">{{$fetchQues->year}}</b></h3>
				  </div>
				  <div class="panel-body">
				    <div class="card card-info" role="alert">@if($fetchQues->question_text == null)<h3 class="text text-center">This question has only image</h3>@else {{$fetchQues->question_text}}@endif
				    </div><br/>
				    <div class="quesbody row">
					    <div class="list-group col-md-3">
						  <a href="#" class="list-group-item @if($fetchQues->answer_option1 == 1) list-group-item-success @endif">Option 1: &nbsp;&nbsp;&nbsp;{{ $fetchQues->option1 }}</a>
						  <a href="#" class="list-group-item @if($fetchQues->answer_option1 == 2) list-group-item-success @endif">Option 2: &nbsp;&nbsp;&nbsp;{{ $fetchQues->option2 }}</a>
						  <a href="#" class="list-group-item @if($fetchQues->answer_option1 == 3) list-group-item-success @endif">Option 3: &nbsp;&nbsp;&nbsp;{{ $fetchQues->option3 }}</a>
						  <a href="#" class="list-group-item @if($fetchQues->answer_option1 == 4) list-group-item-success @endif">Option 4: &nbsp;&nbsp;&nbsp;{{ $fetchQues->option4 }}</a>
						  <a href="#" class="list-group-item @if($fetchQues->answer_option1 == 5) list-group-item-success @endif">Option 5: &nbsp;&nbsp;&nbsp;{{ $fetchQues->option5 }}</a>
						  <p class="label label-success">Correct Option: &nbsp;&nbsp;&nbsp;{{ $fetchQues->answer_option1 }}</p>
						</div>
						<div align="middle" class="card col-md-9">
					    	@if($fetchQues->question_img == null)
					    	<h3 class="text text-center">This question has no image</h3>@else <img src="/img/qwdara/{{$fetchQues->year}}/{{$fetchQues->question_img}}" width="70%" height="70%"/>@endif
					    </div>
					</div>
				  </div>
				</div>

				@endif
			</div>
			
		</div>
	</div>


@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready( function() {
		$('#qv').addClass('active');
	});
</script>
 <script type="text/javascript"> 
    function submitprev() {   document.prevQuest.submit(); } 
</script> 
<script type="text/javascript"> 
    function submitnext() {   document.nextQuest.submit(); } 
</script> 
@endsection