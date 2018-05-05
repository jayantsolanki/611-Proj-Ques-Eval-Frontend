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
			    <h3 class="panel-title">Update your Profile</h3>
			  </div>
			  <div class="panel-body">
			  	<div class="row">
			  		<div class = "col-md-6">
			  			<form method="POST" action="{{ route('createFeatures') }}">
					        <input type="hidden" name="_token" value="{{ csrf_token() }}">
					        <div class="form-group input-group">
					            <select class="form-control" name="year" id="year">
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
			  		<div class = "col-md-6">
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
									<th scope="col">Database Year</th>
									<th scope="col">Accuracy</th>
									<th scope="col">Progress %</th>
									<th scope="col">Algorithm Used</th>
									<th scope="col">Completion Status</th>
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
										{{$Task->accuracy}}
									</th>
									<td>
										@if($Task->progress<100){{$Task->progress}}% <span id="progress" class="glyphicon glyphicon-refresh gly-spin"></span>
										@else
										{{$Task->progress}}%
										@endif
									</td>
									<td>
										{{$Task->algoUsed}}
									</td>
									<td>
										@if($Task->status==0) <span id="progress" class="label label-info">Ongoing</span>
										@else
										<span id="progress" class="label label-success">Completed</span>
										@endif
									</td>
									<td>
										{{$Task->creator}}
									</td>
									<td>
										{{$Task->created_at}}
									</td>
									<td>
										{{$Task->updated_at}}
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
            // top.location.href = "{{route('showTasks')}}";
        });
	});
</script>
@endsection