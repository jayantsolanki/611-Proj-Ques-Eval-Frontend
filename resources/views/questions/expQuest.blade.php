@extends('layouts.questions.master')
@section('title', 'Experimental Question')
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
			<p class="suggestive">Create Experimental Question from existing question</p>
			@if (session('createsuccess'))
			<div class="alert alert-success text-center">
				<p>Experimental Question 1 Added Successfully, <a  target=blank href="{{route('quesViewer')}}/?qid={{session('createsuccess')}}">Visit the created Question 1</a></p>
				<p>Experimental Question 2 Added Successfully, <a  target=blank href="{{route('quesViewer')}}/?qid={{session('createsuccess')}}">Visit the created Question 2</a></p>
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
			@if(($qid>0))
			<!-- <div class="panel panel-default"> -->
			<div class="panel @if($fetchQues->active==1) panel-success @else panel-default @endif">
			  <div class="panel-heading">
			    <h3 class="panel-title">
			    	Question id: <span class="text text-info">{{$fetchQues->quid}}</span><span>&nbsp;&nbsp;Category: <b class="text text-info">@if($fetchQues->category_id == 1) Aptitude @elseif($fetchQues->category_id == 2) Electricals @elseif($fetchQues->category_id == 3) Programming @endif</b>&nbsp;&nbsp;Current Difficulty Level: @if($fetchQues->pre_tag == 0) <b class="text text-success">Easy</b> @elseif($fetchQues->pre_tag == 1)<b class="text text-warning"> Medium </b>@elseif($fetchQues->pre_tag == 2)<b class="text text-danger"> Hard </b> @else NA @endif&nbsp;&nbsp;Predicted Tagging: @if($fetchQues->post_tag == 0) <b class ="text text-success">Easy</b> @elseif($fetchQues->post_tag == 1) <b class ="text text-warning">Medium</b> @elseif($fetchQues->pre_tag == 2) <b class ="text text-danger">Hard</b> @else NA @endif &nbsp;&nbsp;Manual Tagging: @if($fetchQues->pre_tag == 0) <b class ="text text-success">Easy</b> @elseif($fetchQues->pre_tag == 1) <b class ="text text-warning">Medium</b> @elseif($fetchQues->pre_tag == 2) <b class ="text text-danger">Hard</b> @else NA @endif &nbsp;&nbsp; Year: <b class="text text-warning">{{$fetchQues->year}}</b>&nbsp;&nbsp;</span>Rev#: <b class="text text-warning">{{$fetchQues->revision_count}}</b>&nbsp;&nbsp;<span class="pull-right"><a class="label label-info" data-toggle="modal" data-target="#myModal">View History <i class ="glyphicon glyphicon-list-alt"></i></a></span>
			    </h3>
			  </div>
			  <div class="panel-body">
			  	<div class="row">
			  		<div class = "col-md-12">
			  			<h3 class="text-center">Original Question</h3>
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
			  			<div class="row">
			  				<div class="col-md-12">
					  			<form id="editForm" enctype="multipart/form-data" method="POST" action="{{ route('expQuest') }}">
							        <input type="hidden" name="_token" id = "_token" value="{{ csrf_token() }}">
							        <input type="hidden" name="type" id = "type" value="createQues">
							        <input type="hidden" name="qid" id = "qid" value="{{$qid}}">
							        <input type="hidden" name="quid" id = "quid" value="{{$fetchQues->quid}}">
							        <div class="row">
										<div class="col-md-6">
											<hr>
									        <div class="form-group input-group">
									            <span class="input-group-addon" id="sizing-addon1">Question Text <i class=""></i></span>
									            <!-- <input type="text" class="form-control"  placeholder="Question Text, optional" id="qtext" name="qtext" value="{{$fetchQues->question_text}}"> -->
									            <textarea class="form-control" placeholder="Question Text, optional" id="qtext1" name="qtext1" rows="4" cols="10">{{$fetchQues->question_text}}</textarea>
									        </div>
									 
									        <div class="form-group input-group">
									            <span class="input-group-addon" id="sizing-addon1">Year <i class=""></i></span>
									            <select required="" class="form-control" name="year1" id="year1">
									            	<option value="" disabled selected>Select year</option>
									            	@foreach ($years as $year)
									            		<option value="{{$year}}" @if($year == $fetchQues->year) selected @endif>{{$year}}</option>
									            	@endforeach
												</select>
									        </div>
									        <div class="form-group input-group">
												<input type="checkbox" name="addimage1" id="addimage1" value="1">
												<label class = "text text-danger" for="addimage1">&nbsp;No Image</label>
											</div>
									        <div class="form-group input-group">
									            <span class="input-group-addon" id="image-addon1">Upload new image <i class=""></i></span>
									           <input class="form-control" data-preview="#preview1" name="questionimage1" type="file" id="questionimage1">

									           
									        </div>
									        <div class="form-group input-group">
									        	<img class="col-sm-6" id="preview1"  src="" ></img>
									         @if($fetchQues->question_img == null)
									    		<img  width= "60%" height="60%" class="picimg" src="/img/qwdara/noimage.png"/>@else <img width= "50%" height="50%" id="imgpic" class="picimg" src="/img/qwdara/{{$fetchQues->year}}/{{$fetchQues->question_img}}" width= "40%" height="40%" onError="this.onerror=null;this.src='/img/qwdara/default-image.png';"/>@endif
									    	</div>
										</div>
										<div class="col-md-6">
											<hr>
									        <div class="form-group input-group ">
									            <span class="input-group-addon" id="sizing-addon1">Option 1 <i class=""></i></span>
									            <input type="text" class="form-control" id="option11" name="option11" required="" placeholder="option1" aria-describedby="sizing-addon1" value="{{$fetchQues->option1}}">
									        </div>
									       <div class="form-group input-group">
									            <span class="input-group-addon" id="sizing-addon1">Option 2 <i class=""></i></span>
									            <input type="text" class="form-control" id="option12" name="option12" required="" placeholder="option2" aria-describedby="sizing-addon1" value="{{$fetchQues->option2}}">
									        </div>
									       <div class="form-group input-group">
									            <span class="input-group-addon" id="sizing-addon1">Option 3 <i class=""></i></span>
									            <input type="text" class="form-control" id="option13" name="option13" required="" placeholder="option3" aria-describedby="sizing-addon1" value="{{$fetchQues->option3}}">
									        </div>
									        <div class="form-group input-group">
									            <span class="input-group-addon" id="sizing-addon1">Option 4 <i class=""></i></span>
									            <input type="text" class="form-control" id="option14" name="option14" placeholder="option4, optional" aria-describedby="sizing-addon1" value="{{$fetchQues->option4}}">
									        </div>
									        <div class="form-group input-group">
									            <span class="input-group-addon" id="sizing-addon1">Option 5 <i class=""></i></span>
									            <input type="text" class="form-control" id="option15" name="option15" placeholder="option5, optional" aria-describedby="sizing-addon1" value="{{$fetchQues->option5}}">
									        </div>

									        <div class="form-group input-group">
									        	<span class="input-group-addon" id="sizing-addon1">Correct option <i class=""></i></span>
									            <select required="" class="form-control" name="answeroption1" id="answeroption1">
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
												<input required name="difficulty1" type="radio" id="test11" value="0" @if(old('difficulty') == 0) checked @elseif($fetchQues->difficulty_level == 0) checked @endif/>
												<label class = "text text-success" for="test11">&nbsp;Easy</label>&nbsp;&nbsp;
												<input name="difficulty1" type="radio" id="test12" value="1" @if(old('difficulty') == 1) checked @elseif($fetchQues->difficulty_level == 1) checked @endif/>
												<label class = "text text-warning" for="test12">&nbsp;Medium</label>&nbsp;&nbsp;
												<input name="difficulty1" type="radio" id="test13" value="2"@if(old('difficulty') == 2) checked @elseif($fetchQues->difficulty_level == 2) checked @endif/>
												<label class = "text text-danger" for="test13">&nbsp;Hard</label>
											</div>
											<div class="form-group input-group">
												<input required name="category1" type="radio" id="test14" value="1" @if(old('category') == 1) checked @elseif($fetchQues->category_id == 1) checked @endif/>
												<label class = "text text-success" for="test14">&nbsp;Aptitude</label>&nbsp;&nbsp;
												<input name="category1" type="radio" id="test15" value="2"@if(old('category') == 2) checked @elseif($fetchQues->category_id == 2) checked  @endif/>
												<label class = "text text-warning" for="test15">&nbsp;Electricals</label>&nbsp;&nbsp;
												<input name="category1" type="radio" id="test16" value="3"@if(old('category') == 3) checked @elseif($fetchQues->category_id == 3) checked  @endif/>
												<label class = "text text-danger" for="test16">&nbsp;Programming</label>
											</div>
										</div>
										<hr>
									</div>
									<div class="row">
										<div class="col-md-6">
											<hr>
									        <div class="form-group input-group">
									            <span class="input-group-addon" id="sizing-addon1">Question Text <i class=""></i></span>
									            <!-- <input type="text" class="form-control"  placeholder="Question Text, optional" id="qtext" name="qtext" value="{{$fetchQues->question_text}}"> -->
									            <textarea class="form-control" placeholder="Question Text, optional" id="qtext2" name="qtext2" rows="4" cols="10">{{$fetchQues->question_text}}</textarea>
									        </div>
									 
									        <div class="form-group input-group">
									            <span class="input-group-addon" id="sizing-addon1">Year <i class=""></i></span>
									            <select required="" class="form-control" name="year2" id="year2">
									            	<option value="" disabled selected>Select year</option>
									            	@foreach ($years as $year)
									            		<option value="{{$year}}" @if($year == $fetchQues->year) selected @endif>{{$year}}</option>
									            	@endforeach
												</select>
									        </div>
									        <div class="form-group input-group">
												<input type="checkbox" name="addimage2" id="addimage2" value="1">
												<label class = "text text-danger" for="addimage2">&nbsp;No Image</label>
											</div>
									        <div class="form-group input-group">
									            <span class="input-group-addon" id="image-addon1">Upload new image <i class=""></i></span>
									           <input class="form-control" data-preview="#preview2" name="questionimage2" type="file" id="questionimage">

									           
									        </div>
									        <div class="form-group input-group">
									        	<img class="col-sm-6" id="preview2"  src="" ></img>
									         @if($fetchQues->question_img == null)
									    		<img  width= "60%" height="60%" class="picimg" src="/img/qwdara/noimage.png"/>@else <img width= "50%" height="50%" id="imgpic" class="picimg" src="/img/qwdara/{{$fetchQues->year}}/{{$fetchQues->question_img}}" width= "40%" height="40%" onError="this.onerror=null;this.src='/img/qwdara/default-image.png';"/>@endif
									    	</div>
										</div>
										<div class="col-md-6">
											<hr>
									        <div class="form-group input-group ">
									            <span class="input-group-addon" id="sizing-addon1">Option 1 <i class=""></i></span>
									            <input type="text" class="form-control" id="option21" name="option21" required="" placeholder="option1" aria-describedby="sizing-addon1" value="{{$fetchQues->option1}}">
									        </div>
									       <div class="form-group input-group">
									            <span class="input-group-addon" id="sizing-addon1">Option 2 <i class=""></i></span>
									            <input type="text" class="form-control" id="option22" name="option22" required="" placeholder="option2" aria-describedby="sizing-addon1" value="{{$fetchQues->option2}}">
									        </div>
									       <div class="form-group input-group">
									            <span class="input-group-addon" id="sizing-addon1">Option 3 <i class=""></i></span>
									            <input type="text" class="form-control" id="option23" name="option23" required="" placeholder="option3" aria-describedby="sizing-addon1" value="{{$fetchQues->option3}}">
									        </div>
									        <div class="form-group input-group">
									            <span class="input-group-addon" id="sizing-addon1">Option 4 <i class=""></i></span>
									            <input type="text" class="form-control" id="option24" name="option24" placeholder="option4, optional" aria-describedby="sizing-addon1" value="{{$fetchQues->option4}}">
									        </div>
									        <div class="form-group input-group">
									            <span class="input-group-addon" id="sizing-addon1">Option 5 <i class=""></i></span>
									            <input type="text" class="form-control" id="option25" name="option25" placeholder="option5, optional" aria-describedby="sizing-addon1" value="{{$fetchQues->option5}}">
									        </div>

									        <div class="form-group input-group">
									        	<span class="input-group-addon" id="sizing-addon1">Correct option <i class=""></i></span>
									            <select required="" class="form-control" name="answeroption2" id="answeroption2">
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
												<input required name="difficulty2" type="radio" id="test21" value="0" @if(old('difficulty') == 0) checked @elseif($fetchQues->difficulty_level == 0) checked @endif/>
												<label class = "text text-success" for="test21">&nbsp;Easy</label>&nbsp;&nbsp;
												<input name="difficulty2" type="radio" id="test22" value="1" @if(old('difficulty') == 1) checked @elseif($fetchQues->difficulty_level == 1) checked @endif/>
												<label class = "text text-warning" for="test22">&nbsp;Medium</label>&nbsp;&nbsp;
												<input name="difficulty2" type="radio" id="test23" value="2"@if(old('difficulty') == 2) checked @elseif($fetchQues->difficulty_level == 2) checked @endif/>
												<label class = "text text-danger" for="test23">&nbsp;Hard</label>
											</div>
											<div class="form-group input-group">
												<input required name="category2" type="radio" id="test24" value="1" @if(old('category') == 1) checked @elseif($fetchQues->category_id == 1) checked @endif/>
												<label class = "text text-success" for="test24">&nbsp;Aptitude</label>&nbsp;&nbsp;
												<input name="category2" type="radio" id="test25" value="2"@if(old('category') == 2) checked @elseif($fetchQues->category_id == 2) checked  @endif/>
												<label class = "text text-warning" for="test25">&nbsp;Electricals</label>&nbsp;&nbsp;
												<input name="category2" type="radio" id="test26" value="3"@if(old('category') == 3) checked @elseif($fetchQues->category_id == 3) checked  @endif/>
												<label class = "text text-danger" for="test26">&nbsp;Programming</label>
											</div>
										</div>
									</div>

								 	<div class="col-md-12">
								        <div class="form-group">
								            <button style="cursor:pointer" type="submit" class="btn btn-success glyphicon glyphicon-floppy-save pull-right"> Create</button>
								            <!-- <a style="cursor:pointer" class="btn btn-success glyphicon glyphicon-floppy-save pull-right" onclick="updateQues(1,2,3)"> Update</a> -->
								        </div>
								    </div>
							    </form>
							</div>
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
		$('#qexp').addClass('active');
		$('#question').addClass('active');

	});
	$('#addimage').click(function() {
	    $('#questionimage').prop('disabled',this.checked)
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