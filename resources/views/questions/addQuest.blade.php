@extends('layouts.questions.master')
@section('title', 'Question Editor')
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
    body {
	  overflow-x: hidden;
	}

  </style>
@endsection

@section('content')
	<div class="container">
		<div class="row">
			<p class="suggestive">View and Edit Questions</p>
			@if (session('updatesuccess'))
			<div class="alert alert-success text-center">
				<p>Question marked as active Successfully, <a  target=blank href="{{route('quesViewer')}}/?qid={{session('updatesuccess')}}">Visit the updated Question</a></p>
			</div>
			@endif
			@if (session('createsuccess'))
			<div class="alert alert-success text-center">
				<p>Question Added Successfully, <a  target=blank href="{{route('quesViewer')}}/?qid={{session('createsuccess')}}">Visit the created Question</a></p>
			</div>
			@endif
			@if (session('revisesuccess'))
			<div class="alert alert-success text-center">
				<p>New revision created Successfully, <a  target=blank href="{{route('quesViewer')}}/?qid={{session('revisesuccess')}}">Visit the revised Question</a></p>
			</div>
			@endif

			@if (session('error'))
			<div class="alert alert-danger">
				<ul>
				<li>{{session('error')}}</li>
				</ul>
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
		</div>
		<div class="row">
			@if($qid==0)
			<div class="panel panel-default">
			  <div class="panel-heading">
			    <h3 class="panel-title">Create new Question</h3>
			  </div>
			  <div class="panel-body">
			  	<div class="row">
			  		<div class = "col-md-12">
			  			<div class="row">
				  			<form enctype="multipart/form-data" method="POST" action="{{ route('quesEditor') }}">
						        <input type="hidden" name="_token" value="{{ csrf_token() }}">
						        <input type="hidden" name="type" value="newques">
								<!-- <p class="suggestive">Create new Question</p> -->
								<div class="col-md-6">
							        <div class="form-group input-group">
							            <span class="input-group-addon" id="sizing-addon1">Question Description <i class=""></i></span>
							            <!-- <input type="text" class="form-control"  placeholder="Question Text, optional" id="qtext" name="qtext" value="{!!old('qtext')!!}"> -->
							            <textarea class="form-control" placeholder="Question Text, optional" id="qtext" name="qtext" rows="4" cols="10">{!!old('qtext')!!}</textarea>
							        </div>
							 
							        <div class="form-group input-group">
							            <span class="input-group-addon" id="sizing-addon1">Year <i class=""></i></span>
							            <select required="" class="form-control" name="year" id="year">
							            	<option value="" disabled selected>Select year</option>
							            	@foreach ($years as $year)
							            		<option value="{{$year}}" @if($year == ('year')) selected @endif >{{$year}}</option>
							            	@endforeach
										</select>
							        </div>
							        <div class="form-group input-group">
							            <span class="input-group-addon" id="sizing-addon1">Upload image <i class=""></i></span>
							            <input class="form-control" data-preview="#preview" name="questionimage" type="file" id="questionimage">
							        	<img class="col-sm-6" id="preview"  src="" ></img>						           
							        </div>
							        <label class="text text-info">Choose Diffculty and Category</label>
							        <div class="form-group input-group">
										<input required name="difficulty" type="radio" id="test1" value="0" @if(old('difficulty') == '0') checked @endif/>
										<label class = "text text-success" for="test1">&nbsp;Easy</label>&nbsp;&nbsp;
										<input name="difficulty" type="radio" id="test2" value="1" @if(old('difficulty') == '1') checked @endif/>
										<label class = "text text-warning" for="test2">&nbsp;Medium</label>&nbsp;&nbsp;
										<input name="difficulty" type="radio" id="test3" value="2" @if(old('difficulty') == '2') checked @endif/>
										<label class = "text text-danger" for="test3">&nbsp;Hard</label>
									</div>
									<div class="form-group input-group">
										<input required name="category" type="radio" id="test4" value="1" @if(old('category') == '1') checked @endif/>
										<label class = "text text-success" for="test4">&nbsp;Aptitude</label>&nbsp;&nbsp;
										<input name="category" type="radio" id="test5" value="2" @if(old('category') == '2') checked @endif/>
										<label class = "text text-warning" for="test5">&nbsp;Electricals</label>&nbsp;&nbsp;
										<input name="category" type="radio" id="test6" value="3" @if(old('category') == '3') checked @endif/>
										<label class = "text text-danger" for="test6">&nbsp;Programming</label>
									</div>
							    </div><!-- first column -->
							    <div class="col-md-6">
							        <!-- <label class="text text-info">Provide Options along with the correct one</label> -->

							        <div class="form-group input-group">
							            <span class="input-group-addon" id="sizing-addon1">Option 1 <i class=""></i></span>
							            <input type="text" class="form-control" id="option1" name="option1" required="" placeholder="option1" aria-describedby="sizing-addon1" value="{!!old('option1')!!}">
							        </div>
							       <div class="form-group input-group">
							            <span class="input-group-addon" id="sizing-addon1">Option 2 <i class=""></i></span>
							            <input type="text" class="form-control" id="option2" name="option2" required="" placeholder="option2" aria-describedby="sizing-addon1" value="{!!old('option2')!!}">
							        </div>
							       <div class="form-group input-group">
							            <span class="input-group-addon" id="sizing-addon1">Option 3 <i class=""></i></span>
							            <input type="text" class="form-control" id="option3" name="option3" required="" placeholder="option3" aria-describedby="sizing-addon1" value="{!!old('option3')!!}">
							        </div>
							        <div class="form-group input-group">
							            <span class="input-group-addon" id="sizing-addon1">Option 4 <i class=""></i></span>
							            <input type="text" class="form-control" id="option4" name="option4" placeholder="option4, optional" aria-describedby="sizing-addon1" value="{!!old('option4')!!}">
							        </div>
							        <div class="form-group input-group">
							            <span class="input-group-addon" id="sizing-addon1">Option 5 <i class=""></i></span>
							            <input type="text" class="form-control" id="option5" name="option5" placeholder="option5, optional" aria-describedby="sizing-addon1" value="{!!old('option5')!!}">
							        </div>

							        <div class="form-group input-group">
							            <select required="" class="form-control" name="answeroption" id="answeroption">
											<option value="" disabled selected>Choose correct option</option>
											<option value="1" @if(old('answeroption') == 1) selected @endif>Option 1</option>
											<option value="2" @if(old('answeroption') == 2) selected @endif>Option 2</option>
											<option value="3" @if(old('answeroption') == 3) selected @endif>Option 3</option>
											<option value="4" @if(old('answeroption') == 4) selected @endif>Option 4</option>
											<option value="5" @if(old('answeroption') == 5) selected @endif>Option 5</option>
										</select>
							        </div>
							        

							    </div>
							    <div class="col-md-12">
						 
							        <div class="form-group">
							            <button style="cursor:pointer" type="submit" class="btn btn-success glyphicon glyphicon-floppy-save pull-right"> Save</button>
							        </div>
							    </div>
						    </form>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
					    		<button style="cursor:pointer"  class="btn btn-info pull-right glyphicon glyphicon-arrow-left" onclick="goBack1()"> Cancel</button>
					    	</div>
					    </div>
			  			
			  		</div>
			  	</div>
			  </div>
			</div>
			@elseif(($qid>0))
			<!-- <div class="panel panel-default"> -->
			<div class="panel @if($fetchQues->active==1) panel-success @else panel-default @endif">
			  <div class="panel-heading">
			    <h3 class="panel-title">
			    	Question id: <span class="text text-info">{{$fetchQues->quid}}</span><span>&nbsp;&nbsp;Category: <b class="text text-info">@if($fetchQues->category_id == 1) Aptitude @elseif($fetchQues->category_id == 2) Electricals @elseif($fetchQues->category_id == 3) Programming @endif</b>&nbsp;&nbsp;Current Difficulty Level: @if($fetchQues->pre_tag == 0) <b class="text text-success">Easy</b> @elseif($fetchQues->pre_tag == 1)<b class="text text-warning"> Medium </b>@elseif($fetchQues->pre_tag == 2)<b class="text text-danger"> Hard </b> @else NA @endif&nbsp;&nbsp;Predicted Tagging: @if($fetchQues->post_tag == 0) <b class ="text text-success">Easy</b> @elseif($fetchQues->post_tag == 1) <b class ="text text-warning">Medium</b> @elseif($fetchQues->pre_tag == 2) <b class ="text text-danger">Hard</b> @else NA @endif &nbsp;&nbsp;Manual Tagging: @if($fetchQues->pre_tag == 0) <b class ="text text-success">Easy</b> @elseif($fetchQues->pre_tag == 1) <b class ="text text-warning">Medium</b> @elseif($fetchQues->pre_tag == 2) <b class ="text text-danger">Hard</b> @else NA @endif &nbsp;&nbsp; Year: <b class="text text-warning">{{$fetchQues->year}}</b>&nbsp;&nbsp;</span>Rev#: <b class="text text-warning">{{$fetchQues->revision_count}}</b>&nbsp;&nbsp;<span class="pull-right"><a class="label label-info" data-toggle="modal" data-target="#myModal">View History <i class ="glyphicon glyphicon-list-alt"></i></a></span>
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
			  	<div class="row">
			  		<div class = "col-md-12">
			  			<div class="row">
				  			<form id="editForm" enctype="multipart/form-data" method="POST" action="{{ route('quesEditor') }}">
						        <input type="hidden" name="_token" id = "_token" value="{{ csrf_token() }}">
						        <input type="hidden" name="type" id = "type" value="updateques">
						        <input type="hidden" name="qid" id = "qid" value="{{$qid}}">
						        <input type="hidden" name="quid" id = "quid" value="{{$fetchQues->quid}}">
								<!-- <p class="suggestive">Edit Question id {{$fetchQues->quid}}</p> -->
								<!-- <div class="row">
									<div class="col-md-12">
										<span>
											<input type="checkbox" name="active" id="active" value="1">
											<label class = "text text-danger" for="active">&nbsp;Mark this question as active</label>
										</span>
										<span>
											<input type="checkbox" name="active" id="active" value="1">
											<label class = "text text-danger" for="active">&nbsp;Mark this question as active</label>
										</span>
									</div>
								</div> -->
								<div class="col-md-12">
							        <div class="form-group">
							            <span class="pull-right">
											<input type="checkbox" name="editfield" id="editfield" value="1">
											<label class = "label label-danger" for="editfield">&nbsp;Create Revision</label>
										&nbsp;
										&nbsp;
											<input type="checkbox" name="active" id="active" value="1">
											<label class = "label label-success" for="active">&nbsp;Mark this question as active</label>
										</span>
							            <!-- <a style="cursor:pointer" class="btn btn-success glyphicon glyphicon-floppy-save pull-right" onclick="updateQues(1,2,3)"> Update</a> -->
							        </div>
							    </div>
								<div class="col-md-6">
									<hr>
							        <div class="form-group input-group">
							            <span class="input-group-addon" id="sizing-addon1">Question Text <i class=""></i></span>
							            <!-- <input type="text" class="form-control"  placeholder="Question Text, optional" id="qtext" name="qtext" value="{{$fetchQues->question_text}}"> -->
							            <textarea class="form-control" placeholder="Question Text, optional" id="qtext" name="qtext" rows="4" cols="10">{{$fetchQues->question_text}}</textarea>
							        </div>
							 
							        <div class="form-group input-group">
							            <span class="input-group-addon" id="sizing-addon1">Year <i class=""></i></span>
							            <select required="" class="form-control" name="year" id="year">
							            	<option value="" disabled selected>Select year</option>
							            	@foreach ($years as $year)
							            		<option value="{{$year}}" @if($year == $fetchQues->year) selected @endif>{{$year}}</option>
							            	@endforeach
										</select>
							        </div>
							        <div class="form-group input-group">
										<input type="checkbox" name="addimage" id="addimage" value="1">
										<label class = "text text-danger" for="addimage">&nbsp;No Image</label>
									</div>
							        <div class="form-group input-group">
							            <span class="input-group-addon" id="image-addon1">Upload new image <i class=""></i></span>
							           <input class="form-control" data-preview="#preview" name="questionimage" type="file" id="questionimage">

							           
							        </div>
							        <div class="form-group input-group">
							        	<img class="col-sm-6" id="preview"  src="" ></img>
							         @if($fetchQues->question_img == null)
							    		<img  width= "60%" height="60%" class="picimg" src="/img/qwdara/noimage.png"/>@else <img width= "50%" height="50%" id="imgpic" class="picimg" src="/img/qwdara/{{$fetchQues->year}}/{{$fetchQues->question_img}}" width= "40%" height="40%" onError="this.onerror=null;this.src='/img/qwdara/default-image.png';"/>@endif
							    	</div>
								</div>
								<div class="col-md-6">
									<hr>
							        <div class="form-group input-group ">
							            <span class="input-group-addon" id="sizing-addon1">Option 1 <i class=""></i></span>
							            <input type="text" class="form-control" id="option1" name="option1" required="" placeholder="option1" aria-describedby="sizing-addon1" value="{{$fetchQues->option1}}">
							        </div>
							       <div class="form-group input-group">
							            <span class="input-group-addon" id="sizing-addon1">Option 2 <i class=""></i></span>
							            <input type="text" class="form-control" id="option2" name="option2" required="" placeholder="option2" aria-describedby="sizing-addon1" value="{{$fetchQues->option2}}">
							        </div>
							       <div class="form-group input-group">
							            <span class="input-group-addon" id="sizing-addon1">Option 3 <i class=""></i></span>
							            <input type="text" class="form-control" id="option3" name="option3" required="" placeholder="option3" aria-describedby="sizing-addon1" value="{{$fetchQues->option3}}">
							        </div>
							        <div class="form-group input-group">
							            <span class="input-group-addon" id="sizing-addon1">Option 4 <i class=""></i></span>
							            <input type="text" class="form-control" id="option4" name="option4" placeholder="option4, optional" aria-describedby="sizing-addon1" value="{{$fetchQues->option4}}">
							        </div>
							        <div class="form-group input-group">
							            <span class="input-group-addon" id="sizing-addon1">Option 5 <i class=""></i></span>
							            <input type="text" class="form-control" id="option5" name="option5" placeholder="option5, optional" aria-describedby="sizing-addon1" value="{{$fetchQues->option5}}">
							        </div>

							        <div class="form-group input-group">
							        	<span class="input-group-addon" id="sizing-addon1">Correct option <i class=""></i></span>
							            <select required="" class="form-control" name="answeroption" id="answeroption">
											<option value="" disabled selected>Choose correct option</option>
											<option value="1" @if($fetchQues->answer_option1 == 1) selected @endif>Option 1</option>
											<option value="2" @if($fetchQues->answer_option1 == 2) selected @endif>Option 2</option>
											<option value="3" @if($fetchQues->answer_option1 == 3) selected @endif >Option 3</option>
											<option value="4" @if($fetchQues->answer_option1 == 4) selected @endif>Option 4</option>
											<option value="5" @if($fetchQues->answer_option1 == 5) selected @endif>Option 5</option>
										</select>
							        </div>
							        <label class="text text-info">Choose current Diffculty and Category</label>
							        <div class="form-group input-group">
										<input required name="difficulty" type="radio" id="test1" value="0" @if(old('difficulty') == 0) checked @elseif($fetchQues->difficulty_level == 0) checked @endif/>
										<label class = "text text-success" for="test1">&nbsp;Easy</label>&nbsp;&nbsp;
										<input name="difficulty" type="radio" id="test2" value="1" @if(old('difficulty') == 1) checked @elseif($fetchQues->difficulty_level == 1) checked @endif/>
										<label class = "text text-warning" for="test2">&nbsp;Medium</label>&nbsp;&nbsp;
										<input name="difficulty" type="radio" id="test3" value="2"@if(old('difficulty') == 2) checked @elseif($fetchQues->difficulty_level == 2) checked @endif/>
										<label class = "text text-danger" for="test3">&nbsp;Hard</label>
									</div>
									<div class="form-group input-group">
										<input required name="category" type="radio" id="test4" value="1" @if(old('category') == 1) checked @elseif($fetchQues->category_id == 1) checked @endif/>
										<label class = "text text-success" for="test4">&nbsp;Aptitude</label>&nbsp;&nbsp;
										<input name="category" type="radio" id="test5" value="2"@if(old('category') == 2) checked @elseif($fetchQues->category_id == 2) checked  @endif/>
										<label class = "text text-warning" for="test5">&nbsp;Electricals</label>&nbsp;&nbsp;
										<input name="category" type="radio" id="test6" value="3"@if(old('category') == 3) checked @elseif($fetchQues->category_id == 3) checked  @endif/>
										<label class = "text text-danger" for="test6">&nbsp;Programming</label>
									</div>
								</div>
							 	<div class="col-md-12">
							        <div class="form-group">
							            <button style="cursor:pointer" type="submit" class="btn btn-success glyphicon glyphicon-floppy-save pull-right"> Update</button>
							            <!-- <a style="cursor:pointer" class="btn btn-success glyphicon glyphicon-floppy-save pull-right" onclick="updateQues(1,2,3)"> Update</a> -->
							        </div>
							    </div>
						    </form>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
					    		<button style="cursor:pointer"  class="btn btn-info pull-right glyphicon glyphicon-arrow-left" onclick="goBack2()"> Cancel</button>
					    	</div>
					    </div>			  			
			  		</div>
			  	</div>
			  </div>
			</div>
			@endif
			
		</div>
	    
	</div>
@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready( function() {
		$('#qe').addClass('active');
		$('#question').addClass('active');
		// $(':input').attr('disabled')
		$("#editForm :input").prop("disabled", true);
		$("#editfield").prop("disabled", false);
		$("#active").prop("disabled", false);
	});
	$('#addimage').click(function() {
	    $('#questionimage').prop('disabled',this.checked)
	});
	$('#editfield').click(function() {
	    $("#editForm :input").prop("disabled", !this.checked);
	    $("#active").prop("disabled", false);
	    $("#editfield").prop("disabled", false);
	});
	$('#active').click(function() {
	    // $("#editForm :button").prop("disabled", false);
	    $("#editForm :input").prop("disabled", !this.checked);
	    // $("#_token").prop("disabled", false);
	    // $("#type").prop("disabled", false);
	});
	function goBack1() {
	    window.history.back();
	}
	function goBack2() {
	    window.history.back();
	}
	function myFunction() {
	    // alert('The image could not be loaded.');
	    document.getElementById("imgpic").src="/img/qwdara/default-image.png";
	}
	// function updateQues()//defunct, image uploading not working
	// {
	// 	formData = {
	// 		qid: $('#qid').val(),
	// 		type: $('#type').val(),
	// 		qtext: $('#qtext').val(),
	// 		year: $('#year').val(),
	// 		questionimage: $('#questionimage').val(),
	// 		option1: $('#option1').val(),
	// 		option2: $('#option2').val(),
	// 		option3: $('#option3').val(),
	// 		option4: $('#option4').val(),
	// 		option5: $('#option5').val(),
	// 		answeroption: $('#answeroption').val(),
	// 		difficulty: $('#test1').val(),
	// 		category: $('#test2').val()
			
	// 	}
	// 	alert($("#updatequesform")[0])
	// 	// alert(formData.questionimage)
	// 	$.ajax({
	// 	  url: '{{ route('quesEditor') }}',
	// 	  // method: 'POST',
	// 	  data:$("#updatequesform")[0],
	// 	  headers: {
	//          'X-CSRF-Token': $('#_token').val()
	//       },
	// 	  dataType:'json',
	//     async:false,
	//     type:'post',
	//     processData: false,
	//     contentType: false,
	// 	  success: function(data){
	// 	  	alert(data)
	// 	  	// console.log(data)
	// 	  	// $('#qe').addClass('active');
	// 	  	// $('#qv').removeClass('active');
	//       }
	// 	});
	// }
	// function commitQues(qid, action, token)
	// {
	// 	$.ajax({
	// 	  url: '{{ route('quesEditor') }}',
	// 	  method: 'POST',
	// 	  data:
	// 	  	{_token: token,
	// 	  	 qid: qid,
	// 	  	 type:action
	// 	  	},
	// 	  success: function(data){
	// 	  	document.write(data) 
	// 	  	$('#question').addClass('active');
	// 	  	$('#qe').addClass('active');
	// 	  	$('#qv').removeClass('active');
	//       }
	// 	});
	// }
</script>
@endsection