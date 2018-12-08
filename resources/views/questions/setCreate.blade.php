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
					<div class="col-md-12">
				        <div class="input-group col-md-2">
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
								<option value="" disabled selected>Select</option>
								<option value="All" @if($isSelected == 'All') selected @endif>All</option>
								<option value="0" @if($isSelected == '0') selected @endif>No</option>
								<option value="1" @if($isSelected == '1') selected @endif>Yes</option>
							</select>
				        </div>
				        <div class=" input-group col-md-1 pull-right">
				            <button style="cursor:pointer" type="submit" class="btn btn-primary"> Shuffle <span class="glyphicon glyphicon-random"></span></button>
				        </div>
				    </div>
			    </div> 
		            
		    </form>
		    <br/>
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
				    <h3 class="panel-title">
				    	Select Questions for Exam
				    	&nbsp;&nbsp;Category: <b class="text text-info">@if($category == 4) All @elseif($category == 1) Aptitude @elseif($category == 2) Electricals @elseif($category == 3) Programming @endif</b>
				    	&nbsp;&nbsp;
				    	Category: <b class="text text-info">@if($difficulty == 3) All @elseif($difficulty == 0) Easy @elseif($difficulty == 1) Medium @elseif($difficulty == 2) Hard @endif</b>
				    	<span class="pull-right">Total Questions found: <b>{{$count}}</b></span>
				    </h3>
				  </div>
				  <div class="panel-body">
				  	<div class="row">
				  		<div class="col-md-6">
				  			{{ $fetchQues->appends(['year' => $defaultyear, 'category' => $category, 'difficulty' => $difficulty, 'resultCount' => $resultCount, 'isSelected' => $isSelected])->links() }}
				  		</div>
				  		<div id="summary" class="col-md-6">
				  			@include('questions.helper.summary')
				  		</div>
				  	</div>
					<div class="row">
						<!-- begin accordion -->
						<div class="col-md-12">
							<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
								@foreach($fetchQues as $ques)
								  <div id="panel{{$ques->id}}" class="panel @if($ques->for_selectionTest==null or $ques->for_selectionTest==0 or $ques->for_selectionTest=='') panel-danger @else panel-success @endif">
								    <div class="panel-heading" role="tab" id="heading{{$ques->id}}">
								      <h4 class="panel-title">
								        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$ques->id}}" aria-expanded="false" aria-controls="collapse{{$ques->id}}">
								          
								          <b>View Question Id {{$ques->quid }}</b>
								          <span class="glyphicon glyphicon-arrow-down pull-right"></span>
								        </a>
								        <label id="panel{{$ques->id}}" class="badge pull-right"></label>
								      </h4>
								    </div>
								    <div id="collapse{{$ques->id}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{$ques->id}}">
								      <div class="panel-body">
								      	<div class="quesbody row">
									    	<div class="card card-info col-md-5"  role="alert" style="border-right:1px solid #AAAAAA;height:100%">
									    		@if($ques->question_text == null)
									    		<samp class="text text-left"><code>No description:</code><br>This question only has an image</samp>
									    		@else <samp><code>Description:</code><br>{{$ques->question_text}}</samp>
									    		@endif
											    <div class="list-group">
												  <a href="#" class="list-group-item @if($ques->answer_option1 == 1) list-group-item-success @endif">Option 1: &nbsp;&nbsp;&nbsp;{{ $ques->option1 }}</a>
												  <a href="#" class="list-group-item @if($ques->answer_option1 == 2) list-group-item-success @endif">Option 2: &nbsp;&nbsp;&nbsp;{{ $ques->option2 }}</a>
												  <a href="#" class="list-group-item @if($ques->answer_option1 == 3) list-group-item-success @endif">Option 3: &nbsp;&nbsp;&nbsp;{{ $ques->option3 }}</a>
												  <a href="#" class="list-group-item @if($ques->answer_option1 == 4) list-group-item-success @endif">Option 4: &nbsp;&nbsp;&nbsp;{{ $ques->option4 }}</a>
												  <a href="#" class="list-group-item @if($ques->answer_option1 == 5) list-group-item-success @endif">Option 5: &nbsp;&nbsp;&nbsp;{{ $ques->option5 }}</a>
												  <p class="label label-success">Correct Option: &nbsp;&nbsp;&nbsp;{{ $ques->answer_option1 }}</p>
												</div>
											</div>
											<div align="middle" class="card col-md-7">
										    	@if($ques->question_img == null)
										    	<img width= "50%" height="50%" id="imgpic" class="picimg" class="picimg" src="/img/qwdara/noimage.png"/>@else <img width= "50%" height="50%" id="imgpic" class="picimg" src="/img/qwdara/{{$ques->year}}/{{$ques->question_img}}" width= "50%" height="50%" onError="this.onerror=null;this.src='/img/qwdara/default-image.png';"/>@endif
										    </div>
										</div>
										<span class="badge pull-right"> <!-- option -->
										        	<b>Include in next Exam:</b> 
										        	<label>
										        		<input type="radio" name="optradio{{$ques->id}}" id="optradio{{$ques->id}}" @if($ques->for_selectionTest==1) checked @endif onchange="saveSelected('{{$ques->id}}', 1, '{{csrf_token()}}')"> Yes
										        	</label>
										        	&nbsp;&nbsp;
										        	<label>
										        		<input type="radio"  name="optradio{{$ques->id}}" id="optradio{{$ques->id}}" @if($ques->for_selectionTest==null or $ques->for_selectionTest==0 or $ques->for_selectionTest=='') checked @endif onchange="saveSelected('{{$ques->id}}', 0, '{{csrf_token()}}')"> No
										        	</label>

										        	&nbsp;&nbsp;
										        	<a onclick="skipQuestion('{{$ques->id}}')" class="btn btn-warning"> Skip </a>
										        </span>
										<div class="row">
											<div class="col-md-12">
												<hr>
												<form target="_blank" class ="form-inline" method="POST" action="{{ route('quesEditor') }}">
													<input type="hidden" name="_token" value="{{ csrf_token() }}">
													<input type="hidden" name="qid" value="{{$ques->id}}">
													<input type="hidden" name="type" value="editques">
												    <button class="pull-right btn btn-danger" style="cursor:pointer" type="submit">Edit Question</button>
												</form>
												<form target="_blank" class ="form-inline" method="POST" action="{{ route('expQuest') }}">
													<input type="hidden" name="_token" value="{{ csrf_token() }}">
													<input type="hidden" name="qid" value="{{$ques->id}}">
													<input type="hidden" name="type" value="expQuest">
												    <button class="pull-left btn btn-info" style="cursor:pointer" type="submit">Create Experimental Question set</button>
												</form>

											</div>
										</div>
								      </div>
								    </div>
								  </div>
								@endforeach
							</div>
						</div>
						<!-- end of accordion -->
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
    function myFunction() {
	    // alert('The image could not be loaded.');
	    document.getElementById("imgpic").src="/img/qwdara/default-image.png";
	}
	function skipQuestion(qid)
	{
		$('#panel'+qid).fadeOut();
		// $('#panel'+qid).addClass('hidden');
	}
	function saveSelected(qid, action, token)
	{
		// alert('#panel'+qid)
		$('#panel'+qid).removeClass('panel-danger');
		$('#panel'+qid).addClass('panel-warning');
		$('label#panel'+qid).text('Saving..');
		$.ajax({
		  url: '{{ route('quesSelSave') }}',
		  method: 'POST',
		  data:
		  	{_token: token,
		  	 qid: qid,
		  	 action:action
		  	},
		  success: function(data){
		  	data = JSON.parse(data)
		  	if(data.data =='Success')
		  	{
			  	$('#panel'+qid).removeClass('panel-warning');
			  	$('#panel'+qid).removeClass('panel-info');
			  	if(action==0)
					$('#panel'+qid).addClass('panel-danger');
				else
					$('#panel'+qid).addClass('panel-success');
				$('label#panel'+qid).text('Saved');

				$('#summary').fadeOut();
				$('#summary').load('{{ route('quesSelrefresh') }}', function() {
					$('#summary').fadeIn();
				});
				$('#panel'+qid).fadeOut();
				// $('#panel'+qid).addClass('hidden');
			}
			if(data.data =='Error')
			{
				$('label#panel'+qid).text('Error! Please try again');
				$('#panel'+qid).removeClass('panel-warning');
				$('#panel'+qid).addClass('panel-info');
			}
	      }
		});
	
	}
	function setCreate(token)
	{
		// alert(1)
		$('#setMessage').removeClass('label-info');
		$('#setMessage').removeClass('label-success');
		$('#setMessage').removeClass('label-danger');
		$('#setMessage').addClass('label-warning');

		$('.setStatus').removeClass('label-success');
		$('.setStatus').removeClass('label-danger');
		$('.setStatus').removeClass('hidden');
		$('.setStatus').addClass('text-warning');
		$('span.setStatus').text('60 sets are being created, please wait!');
		
		$.ajax({
		  url: '{{ route('setCreate') }}',
		  method: 'POST',
		  data:
		  	{_token: token
		  	},
		  success: function(data){
		  	data = JSON.parse(data)
		  	if(data.data =='Success')
		  	{
		  		$('#setMessage').removeClass('label-warning');
		  		$('#setMessage').addClass('label-success');

		  		$('.setStatus').removeClass('text-warning');
		  		$('.setStatus').addClass('text-success');
		  		$('span.setStatus').text('60 sets created successfully!');
		  		$('i.setStatus').addClass('hidden');
			}
			if(data.data =='Error')
			{
				$('#setMessage').removeClass('label-warning');
		  		$('#setMessage').addClass('label-danger');

				$('.setStatus').removeClass('text-warning');
		  		$('.setStatus').addClass('text-danger');
		  		$('span.setStatus').text('Some error occurred, please try again');
		  		$('i.setStatus').addClass('hidden');
			}
	      }
		});
	
	}
</script> 
@endsection