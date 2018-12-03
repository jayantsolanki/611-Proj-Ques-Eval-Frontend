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
			<p class="suggestive">Browse Questions</p>
			@if($defaultyear==null)
			<p class="error label label-danger">No database found, please add question database</p>
			@else
			<form class ="form-inline" method="POST" action="{{ route('quesViewer') }}">
		        <input type="hidden" name="_token" value="{{ csrf_token() }}">
		        <input type="hidden" name="new" value="filter">
				<div class="row">
			        <div class="input-group col-md-2 corr">
			        	<span class="input-group-addon" id="sizing-addon1">Year <i class=""></i></span>
			            <select required="" class="form-control" name="year" id="year">
			            	<option value="" disabled selected>Select year</option>
			            	@foreach ($years as $year)
			            		<option value="{{$year}}" @if($year == $defaultyear) selected @endif>{{$year}}</option>
			            	@endforeach
						</select>
			        </div>
			        <div class=" input-group col-md-3">
			        	<span class="input-group-addon" id="sizing-addon1">Category <i class=""></i></span>
			            <select required="" class="form-control" name="category" id="category">
							<option value="" disabled selected>Select Category</option>
							<option value="1" @if($category == 1) selected @endif>Aptitude</option>
							<option value="2" @if($category == 2) selected @endif>Electricals</option>
							<option value="3"@if($category == 3) selected @endif >Programming</option>
							<option value="4" @if($category == 4) selected @endif>All</option>
						</select>
			        </div>
			        <div class=" input-group col-md-3">
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
			            <button style="cursor:pointer" type="submit" class="btn btn-info">Change Filter</button>
			        </div>
			    </div> 
		            
		    </form>
		    <br/>
		    <!-- <span>Year <b>{{$defaultyear}}</b> has <b>{{$count}}</b> Questions with Question Id ranging <b>1</b> to <b>{{$count}}</b></span> -->
		    <form name="gotoQuest" class ="form-inline" method="POST" action="{{ route('quesViewer') }}">
				<div class="input-group col-md-offset-0">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="year" value="{{ $defaultyear }}">
					<input type="hidden" name="difficulty" value="{{ $difficulty }}">
					<input type="hidden" name="category" value="{{ $category }}">
					<input type="number" class="form-control" name="current" value="{{ $fetchQues->quid }}">

					<input type="hidden" name="new" value="goto">
				</div>
				<div class=" input-group  col-md-2">
					<button style="cursor:pointer" type="submit" class="btn btn-info">See Question</button>
				</div>
				<span style="font-size: 18px;">Year <b>{{$defaultyear}}</b> has <b>{{$count}}</b> Questions with Question Id ranging <b>1</b> to <b>{{$count}}</b></span>
		    	<a style="cursor:pointer" onclick="editQues('{{$fetchQues->id}}', 'editques', '{{csrf_token()}}')"  class="text text-small btn btn-danger btn pull-right">Edit <i class="glyphicon glyphicon-pencil"></i></a>

			</form>
			
		    @endif
		</div>
		<hr>
		<div class="row">
			<div>
				@if($fetchQues == null)
				<p class="label label-danger">No questions found</p>
				@else
				<div class="panel @if($fetchQues->active==1) panel-success @else panel-default @endif">
				  <div class="panel-heading">
				    <h3 class="panel-title">
				    	Question Id: <b class="text text-info">{{$fetchQues->quid}}</b>&nbsp;&nbsp;Category: <b class="text text-info">@if($fetchQues->category_id == 1) Aptitude @elseif($fetchQues->category_id == 2) Electricals @elseif($fetchQues->category_id == 3) Programming @endif</b>&nbsp;&nbsp;Current Difficulty Level: @if($fetchQues->pre_tag == 0) <b class="text text-success">Easy</b> @elseif($fetchQues->pre_tag == 1)<b class="text text-warning"> Medium </b>@elseif($fetchQues->pre_tag == 2)<b class="text text-danger"> Hard </b> @else NA @endif&nbsp;&nbsp;Predicted Tagging: @if($fetchQues->post_tag == 0) <b class ="text text-success">Easy</b> @elseif($fetchQues->post_tag == 1) <b class ="text text-warning">Medium</b> @elseif($fetchQues->pre_tag == 2) <b class ="text text-danger">Hard</b> @else NA @endif &nbsp;&nbsp;Manual Tagging: @if($fetchQues->pre_tag == 0) <b class ="text text-success">Easy</b> @elseif($fetchQues->pre_tag == 1) <b class ="text text-warning">Medium</b> @elseif($fetchQues->pre_tag == 2) <b class ="text text-danger">Hard</b> @else NA @endif &nbsp;&nbsp; Year: <b class="text text-warning">{{$fetchQues->year}}</b>&nbsp;&nbsp;Rev#: <b class="text text-warning">{{$fetchQues->revision_count}}</b>&nbsp;&nbsp;<span class="pull-right"><a class="label label-info" data-toggle="modal" data-target="#myModal">View History <i class ="glyphicon glyphicon-list-alt"></i></a></span>
				    </h3>
				  </div>
				  <div class="panel-body">
				  	<!-- Modal -->
					<div id="myModal" class="modal fade" role="dialog">
					  <div class="modal-dialog">

					    <!-- Modal content-->
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal">&times;</button>
					        <h4 class="modal-title">Revision history</h4>
					      </div>
					      <div class="modal-body">
					      	@if($fetchQuesHist == null)
					      	<p class="label label-danger">No revision for this question</p>
					      	@else
					        <table class="table table-hover">
							  <thead class="">
							    <tr>
							      <th scope="col">Revision#</th>
							      <th scope="col">Author</th>
							      <th scope="col">Time</th>
							      <th scope="col">Active</th>
							      <th scope="col">Question Id</th>
							    </tr>
							  </thead>
							  <tbody>
							  	@foreach($fetchQuesHist as $hist)
							  	@if($hist->id == $fetchQues->id)
							    	<tr class="bg-success">
							    @else
							    	<tr>
					    		@endif
								      <th scope="row">{{$hist->revision_count}}</th>
								      
								      <!-- <td><a target=blank href="{{route('quesViewer')}}/?qid={{$hist->id}}">View Question</a></td> -->
								      <td><a href="mailto:{{$hist->user->email}}">{{$hist->user->name}}</a></td>
								      <td>{{$hist->updated_at}}</td>
								      <td>@if($hist->active==1) Yes @else No @endif</td>
								      <td>
								      	<form class ="form-inline" method="POST" action="{{ route('quesEditor') }}">
											<input type="hidden" name="_token" value="{{ csrf_token() }}">
											<input type="hidden" name="qid" value="{{$hist->id}}">
											<input type="hidden" name="type" value="editques">
										    <button style="cursor:pointer" type="submit" class="btn">Edit Question</button>
										</form>
								      </td>
								    </tr>
							    @endforeach
							  </tbody>
							</table>
							@endif
					      </div>
					      <div class="modal-footer">
					        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					      </div>
					    </div>

					  </div>
					</div>
				  	<div class="ques row">
						<div class="pull-left">
							<form name="prevQuest" class ="form-inline" method="POST" action="{{ route('quesViewer') }}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="year" value="{{ $defaultyear }}">
								<input type="hidden" name="difficulty" value="{{ $difficulty }}">
								<input type="hidden" name="category" value="{{ $category }}">
								<input type="hidden" name="current" value="{{ $fetchQues->quid }}">
								<input type="hidden" name="new" value="previous">
							  <ul class="pagination justify-content-end">
							    <li class="page-item @if($previous == 0) disabled @endif">
							      <a class="page-link" href="@if($previous == 0) # @else javascript: submitprev() @endif" tabindex="-1"><< Prev</a>
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
							      <a class="page-link" href="@if($next == 0) # @else javascript: submitnext() @endif">Next >></a>
							    </li>
							  </ul>
							</form>
							
						</div>
					</div>
				    <div class="quesbody row">
				    	<div class="card card-info col-md-5"  role="alert" style="border-right:1px solid #AAAAAA;height:100%">
				    		@if($fetchQues->question_text == null)
				    		<samp class="text text-left"><code>No description:</code><br>This question only has an image</samp>
				    		@else <samp><code>Description:</code><br>{{$fetchQues->question_text}}</samp>
				    		@endif
				    		
						    <div class="list-group">
							  <a href="#" class="list-group-item @if($fetchQues->answer_option1 == 1) list-group-item-success @endif">Option 1: &nbsp;&nbsp;&nbsp;{{ $fetchQues->option1 }}</a>
							  <a href="#" class="list-group-item @if($fetchQues->answer_option1 == 2) list-group-item-success @endif">Option 2: &nbsp;&nbsp;&nbsp;{{ $fetchQues->option2 }}</a>
							  <a href="#" class="list-group-item @if($fetchQues->answer_option1 == 3) list-group-item-success @endif">Option 3: &nbsp;&nbsp;&nbsp;{{ $fetchQues->option3 }}</a>
							  <a href="#" class="list-group-item @if($fetchQues->answer_option1 == 4) list-group-item-success @endif">Option 4: &nbsp;&nbsp;&nbsp;{{ $fetchQues->option4 }}</a>
							  <a href="#" class="list-group-item @if($fetchQues->answer_option1 == 5) list-group-item-success @endif">Option 5: &nbsp;&nbsp;&nbsp;{{ $fetchQues->option5 }}</a>
							  <p class="label label-success">Correct Option: &nbsp;&nbsp;&nbsp;{{ $fetchQues->answer_option1 }}</p>
							</div>
						</div>
						<div align="middle" class="card col-md-7">
					    	@if($fetchQues->question_img == null)
					    	<img width= "50%" height="50%" id="imgpic" class="picimg" class="picimg" src="/img/qwdara/noimage.png"/>@else <img width= "50%" height="50%" id="imgpic" class="picimg" src="/img/qwdara/{{$fetchQues->year}}/{{$fetchQues->question_img}}" width= "50%" height="50%" onError="this.onerror=null;this.src='/img/qwdara/default-image.png';"/>@endif
					    </div>
					    
					</div>
					<hr>
					
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
		$('#qv').addClass('active');
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