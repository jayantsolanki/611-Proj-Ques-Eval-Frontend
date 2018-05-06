@extends('layouts.questions.master')
@section('title', 'Task Viewer')
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
	.gly-spin {
	  -webkit-animation: spin 2s infinite linear;
	  -moz-animation: spin 2s infinite linear;
	  -o-animation: spin 2s infinite linear;
	  animation: spin 2s infinite linear;
	}
	@-moz-keyframes spin {
	  0% {
	    -moz-transform: rotate(0deg);
	  }
	  100% {
	    -moz-transform: rotate(359deg);
	  }
	}
	@-webkit-keyframes spin {
	  0% {
	    -webkit-transform: rotate(0deg);
	  }
	  100% {
	    -webkit-transform: rotate(359deg);
	  }
	}
	@-o-keyframes spin {
	  0% {
	    -o-transform: rotate(0deg);
	  }
	  100% {
	    -o-transform: rotate(359deg);
	  }
	}
	@keyframes spin {
	  0% {
	    -webkit-transform: rotate(0deg);
	    transform: rotate(0deg);
	  }
	  100% {
	    -webkit-transform: rotate(359deg);
	    transform: rotate(359deg);
	  }
	}
	.gly-rotate-90 {
	  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=1);
	  -webkit-transform: rotate(90deg);
	  -moz-transform: rotate(90deg);
	  -ms-transform: rotate(90deg);
	  -o-transform: rotate(90deg);
	  transform: rotate(90deg);
	}
	.gly-rotate-180 {
	  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=2);
	  -webkit-transform: rotate(180deg);
	  -moz-transform: rotate(180deg);
	  -ms-transform: rotate(180deg);
	  -o-transform: rotate(180deg);
	  transform: rotate(180deg);
	}
	.gly-rotate-270 {
	  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
	  -webkit-transform: rotate(270deg);
	  -moz-transform: rotate(270deg);
	  -ms-transform: rotate(270deg);
	  -o-transform: rotate(270deg);
	  transform: rotate(270deg);
	}
	.gly-flip-horizontal {
	  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=0, mirror=1);
	  -webkit-transform: scale(-1, 1);
	  -moz-transform: scale(-1, 1);
	  -ms-transform: scale(-1, 1);
	  -o-transform: scale(-1, 1);
	  transform: scale(-1, 1);
	}
	.gly-flip-vertical {
	  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=2, mirror=1);
	  -webkit-transform: scale(1, -1);
	  -moz-transform: scale(1, -1);
	  -ms-transform: scale(1, -1);
	  -o-transform: scale(1, -1);
	  transform: scale(1, -1);
	}
	.hideSpan 

		{ display: none; }
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
			<p class="suggestive"><span class=""></span> Perform machine learning task for difficulty prediction here</p>
		</div>
		<div class="row">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    <h3 class="panel-title">Predict difficulty level</h3>
			  </div>
			  <div class="panel-body">
			  	<div class="row">
			  		<div class = "col-md-12">
			  			<form method="POST" action="{{ route('createFeatures') }}">
					        <input type="hidden" name="_token" value="{{ csrf_token() }}">
					        <div class="form-group input-group col-md-4">
					        	<span class="input-group-addon" id="sizing-addon1">Choose the Database year<i class=""></i></span>
					            <select class="form-control" name="year" id="year">
					            	<option value="" disabled selected>Choose correct option</option>
					            	@foreach ($years as $year)
					            		<option value="{{$year}}" @if($year == $defaultyear) selected @endif>{{$year}}</option>
					            	@endforeach
								</select>
					        </div>
					        <div class="form-group">
					            <button style="cursor:pointer" type="submit" class="btn btn-success pull-right">Create Features</button>
					        </div>
					    </form>
			  		</div>
			  	</div>
			  	@if($hasFeatures == 1)
			  	<div class="row">
			  		<hr>
			  		<div class = "col-md-12">
			  			<label>Features created, You can now run the analysis for Database year {{$defaultyear}}</label>
			  			<form action = "http://localhost:8888/doAnalysis" id="analysis">
					        <input type="hidden" name="_token" value="{{ csrf_token() }}">
					        <input type="hidden" name="year" value="{{ $defaultyear }}">
					        <input type="hidden" name="taskId" value="{{ $taskId }}">	      
					        <div class="form-group">
					            <button style="cursor:pointer" type="submit" class="btn btn-success pull-right">Run Analysis</button>
					        </div>
					    </form>
			  		</div>
			  	</div>
			  	@endif
			  	@if($runningTask >= 1)
			  	<div class="row">
			  		<hr>
			  		<div class = "error alert alert-info">
			  			<h4 class="text text-center">New analysis cannot be done before the previous task is finished</h4>
			  		</div>
			  	</div>
			  	@endif
			  	<div class="row">
			  		<hr>
			  		
			  		<div class = "col-md-12">
			  			<h4 class="text text-center"><b>Analysis History</b></h4>
			  			<table class="table">
							  <thead>
							    <tr>
									<th scope="col">TaskId</th>
									<th scope="col">DB Year</th>
									<th scope="col">Accuracy %</th>
									<th scope="col">Progress %</th>
									<th scope="col">Algorithm</th>
									<th scope="col">Status</th>
									<th scope="col">Created by</th>
									<th scope="col">Created at</th>
									<th scope="col">Last Updated</th>
							    </tr>
							  </thead>
							  <tbody>
							  	@foreach ($Tasks as $Task)
							  	<tr>
									<th scope="row">
										{{$Task->id}}
									</th>
									<th scope="row">
										{{$Task->year}}
									</th>
									<th scope="row">
										<span id="accuracy{{$Task->id}}">{{$Task->accuracy}}%</span>
									</th>
									<td>
										@if($Task->progress==0)<b id="progress{{$Task->id}}">{{$Task->progress}}%</b> <span id="circle{{$Task->id}}" class="glyphicon glyphicon-stop"></span>
										@elseif($Task->progress<100 && $Task->progress>0)<b id="progress{{$Task->id}}">{{$Task->progress}}%</b><span id="circle{{$Task->id}}" class="glyphicon glyphicon-refresh gly-spin"></span>
										@else
										<b id="progress{{$Task->id}}">{{$Task->progress}}%</b>
										@endif
									</td>
									<td>
										{{$Task->algoUsed}}
									</td>
									<td>
										@if($Task->status==0) <span id="status{{$Task->id}}" class="label label-warning">Created</span>
										@elseif($Task->status==1) <span id="status{{$Task->id}}" class="label label-info">Ongoing</span>
										@else
										<span id="sstatus{{$Task->id}}">
											<form name="gotoQuest" class ="form-inline" method="POST" action="{{ route('showStats') }}">
												<input type="hidden" name="_token" value="{{ csrf_token() }}">
												<input type="hidden" name="taskId" value="{{$Task->id}}">
												<input type="hidden" name="year" value="{{$Task->year}}">
												<button style="cursor:pointer" type="submit" class="label btn-success">Completed, View Info</button>
											</form>
										</span>
										
										@endif
									</td>
									<td>
										{{$Task->creator}}
									</td>
									<td>
										{{$Task->created_at}}
									</td>
									<td>
										<span id="time{{$Task->id}}">{{$Task->updated_at}}</span> <a href = "{{route('showTasks')}}/?id={{$Task->id}}"><span title="delete dormant task" id="delete" class="glyphicon glyphicon-remove"></span></a>
									</td>
							    </tr>
							  	@endforeach
							  </tbody>
						</table>
			  		</div>
			  	</div>
			  	
			  </div>
			</div>
			
		</div>

	    
	</div>

@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready( function() {
		$('#tv').addClass('active');
		setInterval(checkProgress,1000);
	});
	$("#analysis").submit(function(event){
		/* stop form from submitting normally */
        event.preventDefault();

        /* get some values from elements on the page: */
        var $form = $(this),
            year = $form.find('input[name="year"]').val(),
            taskId = $form.find('input[name="taskId"]').val(),
            url = $form.attr('action');

        /* Send the data using post */
        var posting = $.post(url, {
            year: year,
            taskId: taskId
        });

        /* Put the results in a div */
        posting.done(function(data) {
            var content = $(data).find('#content');
            // $("#result").empty().append(content);
            content = data
            alert(content);
            top.location.href = "{{route('showTasks')}}";
        });
	});

	function checkProgress(){
        $.ajax({
		    method: 'POST', // Type of response and matches what we said in the route
		    url: "{{route('checkProgress')}}", // This is the url we gave in the route
		    data: {'_token' : "{{ csrf_token() }}"}, // a JSON object to send back
		    success: function(response){ // What to do if we succeed
		        console.log(response); 
		        console.log(response['id'])
		        var progress = '#progress'+response['id']
		        var circle = '#circle'+response['id']
		        var status = '#status'+response['id']
		        var sstatus = '#sstatus'+response['id']
		        var accuracy = '#accuracy'+response['id']
		        var time = '#time'+response['id']
		        $(progress).html(response['progress']+"%")
		        $(time).html(response['updated_at'])
		        // $(sstatus).remove();
		        if(response['progress']>0 && response['progress']<100){
		        	$(circle).attr('class', 'glyphicon glyphicon-refresh gly-spin')
		        	$(status).html("Ongoing")
		        	$(status).attr('class', 'label label-info');
		        	$(sstatus).attr('class', 'hideSpan');
		        }
		        if(response['progress']==100){
		        	$(circle).removeClass( "glyphicon glyphicon-refresh gly-spin" )
		        	$(status).html("<form name='gotoQuest' class ='form-inline' method='POST' action='{{ route('showStats') }}'>												<input type='hidden' name='_token' value='{{ csrf_token() }}'>												<input type='hidden' name='taskId' value='{{$Task->id}}'>												<input type='hidden' name='year' value='{{$Task->year}}''>												<button style='cursor:pointer' type='submit' class='label btn-success'>Completed, View Info</button>											</form>");
		        	// $(sstatus).removeClass('hideSpan');
		        	// $(status).attr('class', 'hideSpan');
		        	$(status).removeClass('label label-info');
		        	$(accuracy).html(response['accuracy']+"%")
		        	// hideSpan
		        }
		        
		    },
		    error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
		        console.log(JSON.stringify(jqXHR));
		        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
		    }
		});
	}
</script>
@endsection