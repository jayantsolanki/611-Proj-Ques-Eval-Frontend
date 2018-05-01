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
				<p>Question Updated Successfully, <a  target=blank href="{{route('quesViewer')}}/?qid={{session('updatesuccess')}}">Visit the updated Question</a></p>
			</div>
			@endif
			@if (session('createsuccess'))
			<div class="alert alert-success text-center">
				<p>Question Added Successfully, <a  target=blank href="{{route('quesViewer')}}/?qid={{session('createsuccess')}}">Visit the created Question</a></p>
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
			    <h3 class="panel-title">Question Control Panel</h3>
			  </div>
			  <div class="panel-body">
			  	<div class="row">
			  		<div class = "col-md-10">
			  			<form method="POST" action="{{ route('quesEditor') }}">
					        <input type="hidden" name="_token" value="{{ csrf_token() }}">
					        <input type="hidden" name="type" value="newques">
							<p class="suggestive">Create new Question</p>
							<div class="row">
						</div>
					        <div class="form-group input-group col-md-6">
					            <span class="input-group-addon" id="sizing-addon1">Question Text <i class=""></i></span>
					            <input type="text" class="form-control"  placeholder="Question Text, optional" id="qtext" name="qtext" value="{!!old('qtext')!!}">
					        </div>
					 
					        <div class="form-group input-group col-md-3">
					            <span class="input-group-addon" id="sizing-addon1">Year <i class=""></i></span>
					            <input type="text" class="form-control" id="year" name="year" required="" placeholder="Year like <?php echo date('Y');?>" aria-describedby="sizing-addon1" value="{!!old('year')!!}">
					        </div>
					        <hr>
					        <label class="text text-info">Choose Diffculty and Category</label>
					        <div class="form-group input-group">
								<input required name="difficulty" type="radio" id="test1" value="0" @if(old('difficulty') == 0) checked @endif/>
								<label class = "text text-success" for="test1">&nbsp;Easy</label>&nbsp;&nbsp;
								<input name="difficulty" type="radio" id="test2" value="1" @if(old('difficulty') == 1) checked @endif/>
								<label class = "text text-warning" for="test2">&nbsp;Medium</label>&nbsp;&nbsp;
								<input name="difficulty" type="radio" id="test3" value="2" @if(old('difficulty') == 2) checked @endif/>
								<label class = "text text-danger" for="test3">&nbsp;Hard</label>
							</div>
							<div class="form-group input-group">
								<input required name="category" type="radio" id="test4" value="1" @if(old('category') == 1) checked @endif/>
								<label class = "text text-success" for="test4">&nbsp;Aptitude</label>&nbsp;&nbsp;
								<input name="category" type="radio" id="test5" value="2"@if(old('category') == 2) checked @endif/>
								<label class = "text text-warning" for="test5">&nbsp;Electricals</label>&nbsp;&nbsp;
								<input name="category" type="radio" id="test6" value="3"@if(old('category') == 3) checked @endif/>
								<label class = "text text-danger" for="test6">&nbsp;Programming</label>
							</div>
					        <hr>
					        <label class="text text-info">Provide Options along with the correct one</label>

					        <div class="form-group input-group col-md-6">
					            <span class="input-group-addon" id="sizing-addon1">Option 1 <i class=""></i></span>
					            <input type="text" class="form-control" id="option1" name="option1" required="" placeholder="option1" aria-describedby="sizing-addon1" value="{!!old('option1')!!}">
					        </div>
					       <div class="form-group input-group col-md-6">
					            <span class="input-group-addon" id="sizing-addon1">Option 2 <i class=""></i></span>
					            <input type="text" class="form-control" id="option2" name="option2" required="" placeholder="option2" aria-describedby="sizing-addon1" value="{!!old('option2')!!}">
					        </div>
					       <div class="form-group input-group col-md-6">
					            <span class="input-group-addon" id="sizing-addon1">Option 3 <i class=""></i></span>
					            <input type="text" class="form-control" id="option3" name="option3" required="" placeholder="option3" aria-describedby="sizing-addon1" value="{!!old('option3')!!}">
					        </div>
					        <div class="form-group input-group col-md-6">
					            <span class="input-group-addon" id="sizing-addon1">Option 4 <i class=""></i></span>
					            <input type="text" class="form-control" id="option4" name="option4" placeholder="option4, optional" aria-describedby="sizing-addon1" value="{!!old('option4')!!}">
					        </div>
					        <div class="form-group input-group col-md-6">
					            <span class="input-group-addon" id="sizing-addon1">Option 5 <i class=""></i></span>
					            <input type="text" class="form-control" id="option5" name="option5" placeholder="option5, optional" aria-describedby="sizing-addon1" value="{!!old('option5')!!}">
					        </div>

					        <div class="form-group input-group col-md-6">
					            <select required="" class="form-control" name="answeroption" id="answeroption">
									<option value="" disabled selected>Choose correct option</option>
									<option value="1" @if(old('answeroption') == 1) selected @endif>Option 1</option>
									<option value="2" @if(old('answeroption') == 2) selected @endif>Option 2</option>
									<option value="3" @if(old('answeroption') == 3) selected @endif >Option 3</option>
									<option value="4" @if(old('answeroption') == 4) selected @endif>Option 4</option>
									<option value="5" @if(old('answeroption') == 5) selected @endif>Option 5</option>
								</select>
					        </div>
					        <hr>
					 
					        <div class="form-group pull-right">
					            <button style="cursor:pointer" type="submit" class="btn btn-success glyphicon glyphicon-floppy-save">Save</button>
					        </div>
					    </form>
			  			
			  		</div>
			  	</div>
			  </div>
			</div>
			@elseif(($qid>0))
			<div class="panel panel-default">
			  <div class="panel-heading">
			    <h3 class="panel-title">Question Control Panel</h3>
			  </div>
			  <div class="panel-body">
			  	<div class="row">
			  		<div class = "col-md-10">
			  			<form method="POST" action="{{ route('quesEditor') }}">
					        <input type="hidden" name="_token" value="{{ csrf_token() }}">
					        <input type="hidden" name="type" value="updateques">
					        <input type="hidden" name="qid" value="{{$qid}}">
							<p class="suggestive">Edit Question id {{$qid}}</p>
							<div class="row">
						</div>
					        <div class="form-group input-group col-md-6">
					            <span class="input-group-addon" id="sizing-addon1">Question Text <i class=""></i></span>
					            <input type="text" class="form-control"  placeholder="Question Text, optional" id="qtext" name="qtext" value="{{$fetchQues->question_text}}">
					        </div>
					 
					        <div class="form-group input-group col-md-3">
					            <span class="input-group-addon" id="sizing-addon1">Year <i class=""></i></span>
					            <input type="text" class="form-control" id="year" name="year" required="" placeholder="Year like <?php echo date('Y');?>" aria-describedby="sizing-addon1" value="{{$fetchQues->year}}">
					        </div>
					        <hr>
					        <label class="text text-info">Choose Diffculty and Category</label>
					        <div class="form-group input-group">
								<input required name="difficulty" type="radio" id="test1" value="1" @if(old('difficulty') == 0) checked @elseif($fetchQues->pre_tag == 0) checked @endif/>
								<label class = "text text-success" for="test1">&nbsp;Easy</label>&nbsp;&nbsp;
								<input name="difficulty" type="radio" id="test2" value="2"@if(old('difficulty') == 1) checked @elseif($fetchQues->pre_tag == 1) checked @endif/>
								<label class = "text text-warning" for="test2">&nbsp;Medium</label>&nbsp;&nbsp;
								<input name="difficulty" type="radio" id="test3" value="2"@if(old('difficulty') == 1) checked @elseif($fetchQues->pre_tag == 2) checked @endif/>
								<label class = "text text-danger" for="test3">&nbsp;Hard</label>
							</div>
							<div class="form-group input-group">
								<input required name="category" type="radio" id="test4" value="1" @if(old('category') == 1) checked @elseif($fetchQues->category_id == 1) checked @endif/>
								<label class = "text text-success" for="test4">&nbsp;Aptitude</label>&nbsp;&nbsp;
								<input name="category" type="radio" id="test5" value="2"@if(old('category') == 2) checked @elseif($fetchQues->category_id == 2) checked  @endif/>
								<label class = "text text-warning" for="test5">&nbsp;Electricals</label>&nbsp;&nbsp;
								<input name="category" type="radio" id="test6" value="2"@if(old('category') == 3) checked @elseif($fetchQues->category_id == 3) checked  @endif/>
								<label class = "text text-danger" for="test6">&nbsp;Programming</label>
							</div>
					        <hr>
					        <label class="text text-info">Provide Options along with the correct one</label>

					        <div class="form-group input-group col-md-6">
					            <span class="input-group-addon" id="sizing-addon1">Option 1 <i class=""></i></span>
					            <input type="text" class="form-control" id="option1" name="option1" required="" placeholder="option1" aria-describedby="sizing-addon1" value="{{$fetchQues->option1}}">
					        </div>
					       <div class="form-group input-group col-md-6">
					            <span class="input-group-addon" id="sizing-addon1">Option 2 <i class=""></i></span>
					            <input type="text" class="form-control" id="option2" name="option2" required="" placeholder="option2" aria-describedby="sizing-addon1" value="{{$fetchQues->option2}}">
					        </div>
					       <div class="form-group input-group col-md-6">
					            <span class="input-group-addon" id="sizing-addon1">Option 3 <i class=""></i></span>
					            <input type="text" class="form-control" id="option3" name="option3" required="" placeholder="option3" aria-describedby="sizing-addon1" value="{{$fetchQues->option3}}">
					        </div>
					        <div class="form-group input-group col-md-6">
					            <span class="input-group-addon" id="sizing-addon1">Option 4 <i class=""></i></span>
					            <input type="text" class="form-control" id="option4" name="option4" placeholder="option4, optional" aria-describedby="sizing-addon1" value="{{$fetchQues->option4}}">
					        </div>
					        <div class="form-group input-group col-md-6">
					            <span class="input-group-addon" id="sizing-addon1">Option 5 <i class=""></i></span>
					            <input type="text" class="form-control" id="option5" name="option5" placeholder="option5, optional" aria-describedby="sizing-addon1" value="{{$fetchQues->option5}}">
					        </div>

					        <div class="form-group input-group col-md-6">
					            <select required="" class="form-control" name="answeroption" id="answeroption">
									<option value="" disabled selected>Choose correct option</option>
									<option value="1" @if($fetchQues->answer_option1 == 1) selected @endif>Option 1</option>
									<option value="2" @if($fetchQues->answer_option1 == 2) selected @endif>Option 2</option>
									<option value="3" @if($fetchQues->answer_option1 == 3) selected @endif >Option 3</option>
									<option value="4" @if($fetchQues->answer_option1 == 4) selected @endif>Option 4</option>
									<option value="5" @if($fetchQues->answer_option1 == 5) selected @endif>Option 5</option>
								</select>
					        </div>
					        <hr>
					 
					        <div class="form-group pull-right">
					            <button style="cursor:pointer" type="submit" class="glyphicon glyphicon-ok btn btn-success">Update</button>
					        </div>
					    </form>
			  			
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
	});
</script>
@endsection