@extends('layouts.questions.master')
@section('title', 'Question Set')
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
      margin: 0px 00px 0px 0px;
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
	input[type=number]::-webkit-inner-spin-button, 
	input[type=number]::-webkit-outer-spin-button {  

	   opacity: 1;

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
			<p class="suggestive">Question Set Management</p>
			@if($defaultyear==null)
			<p class="error label label-danger">No database found, please add question database</p>
			@else
			<form class ="form-inline" method="POST" action="{{ route('quesSet') }}">
		        <input type="hidden" name="_token" value="{{ csrf_token() }}">
		        <input type="hidden" name="new" value="filter">
				<div class="row">
			        <div class="input-group col-md-2 corr">
			        	<span class="input-group-addon" id="sizing-addon1">Year <i class=""></i></span>
			            <select required="" class="form-control" name="year" id="year">
			            	<option value="" disabled selected>Select year</option>
			            	<option value="All"  @if($defaultyear=='All') selected @endif>All</option>
			            	@foreach ($years as $year)
			            		<option value="{{$year}}" @if($year == $defaultyear) selected @endif>{{$year}}</option>
			            	@endforeach
						</select>
			        </div>
			        <div class=" input-group col-md-2">
			        	<span class="input-group-addon" id="sizing-addon1">Category <i class=""></i></span>
			            <select required="" class="form-control" name="category" id="category">
							<option value="" disabled selected>Select Category</option>
							<option value="1" @if($category == 1) selected @endif>Aptitude</option>
							<option value="2" @if($category == 2) selected @endif>Electricals</option>
							<option value="3"@if($category == 3) selected @endif >Programming</option>
							<option value="4" @if($category == 4) selected @endif>All</option>
						</select>
			        </div>
			        <div class=" input-group col-md-2">
			        	<span class="input-group-addon" id="sizing-addon1">Difficulty<i class=""></i></span>
			            <select required="" class="form-control" name="difficulty" id="difficulty">
							<option value="" disabled selected>Select Difficulty level</option>
							<option value="0" @if($difficulty == 0) selected @endif>Easy</option>
							<option value="1" @if($difficulty == 1) selected @endif>Medium</option>
							<option value="2"@if($difficulty == 2) selected @endif >Hard</option>
							<option value="3" @if($difficulty == 3) selected @endif>All</option>
						</select>
			        </div>
			        <div class=" input-group col-md-2">
			        	<span class="input-group-addon" id="sizing-addon1">Result Size<i class=""></i></span>
			        	<select required="" class="form-control" name="resultCount" id="resultCount">
			        		<option value="" disabled selected>#</option>
			        		@for ($i=10; $i<=100; $i=$i+10)
								<option value="{{$i}}" @if($resultCount == $i) selected @endif>{{$i}}</option>
							@endfor
						</select>
			        </div>
			        <div class=" input-group col-md-2">
			        	<span class="input-group-addon" id="sizing-addon1">Selected for Test<i class=""></i></span>
			            <select required="" class="form-control" name="isSelected" id="isSelected">
							<option value="" disabled selected>Select Yes or No</option>
							<option value="0" @if($isSelected == 0) selected @endif>No</option>
							<option value="1" @if($isSelected == 1) selected @endif>Yes</option>
						</select>
			        </div>
			        <div class=" input-group col-md-1">
			            <button style="cursor:pointer" type="submit" class="btn btn-info">Search <span class="glyphicon glyphicon-search"></span></button>
			        </div>
			    </div> 
		            
		    </form>
		    <br/>
		    <!-- <span>Year <b>{{$defaultyear}}</b> has <b>{{$count}}</b> Questions with Question Id ranging <b>1</b> to <b>{{$count}}</b></span> -->
		    <form name="gotoQuest" class ="form-inline" method="POST" action="{{ route('quesSet') }}">
				<div class="input-group col-md-offset-0">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="year" value="{{ $defaultyear }}">
					<input type="hidden" name="difficulty" value="{{ $difficulty }}">
					<input type="hidden" name="category" value="{{ $category }}">
					<input type="number" class="form-control" name="current" value="1">

					<input type="hidden" name="new" value="goto">
				</div>
				<div class=" input-group  col-md-2">
					<button style="cursor:pointer" type="submit" class="btn btn-info">Starting Question Id</button>
				</div>
			</form>
			
		    @endif
		</div>
		<hr>
		<div class="row">
			<div>
				@if($fetchQues == null)
				<p class="label label-danger">No questions found</p>
				@else
				<div class="panel panel-default">
				  <div class="panel-heading">
				    <h3 class="panel-title"> Select Questions for Selection Test
				    </h3>
				  </div>
				  <div class="panel-body">
					<div class="row">
						<div class=col-md-12>
							{{ $fetchQues->appends(['sort' => 'votes'])->links() }}
							<table class="table table-striped">
							 <thead>
							 <tr>
							    <th>ID</th>
							    <th>Question Text</th>
							    <th>Author</th>
							 </tr>
							 </thead>
							 <tbody>
							    @foreach($fetchQues as $ques)
							    <tr>
							       <td>{{ $ques->id }}</td>
							       <td>{{ $ques->question_text }}</td>
							       <td>{{ $ques->user_id }}</td>
							    </tr>
							    @endforeach
							 </tbody>
							</table>
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
<script type="application/javascript"> 
	$(document).ready( function() {
		$('#qs').addClass('active');
		$('#question').addClass('active');
	});

	function submitprev() {
	   document.prevQuest.submit(); 
	}
    function submitnext() {   
    	document.nextQuest.submit(); 
    } 
    function myFunction() {
	    // alert('The image could not be loaded.');
	    document.getElementById("imgpic").src="/img/qwdara/default-image.png";
	}

	function editQues(qid, action, token)
	{
		$.ajax({
		  url: '{{ route('quesEditor') }}',
		  method: 'POST',
		  data:
		  	{_token: token,
		  	 qid: qid,
		  	 type:action
		  	},
		  success: function(data){
		  	document.write(data) 
		  	$('#qe').addClass('active');
		  	$('#question').addClass('active');
		  	$('#qv').removeClass('active');
		  	$("#editForm :input").prop("disabled", true);
		  	$("#editfield").prop("disabled", false);
		  	$("#active").prop("disabled", false);
		  	$("#_token").prop("disabled", false);
	      }
		});
	}
</script> 
@endsection