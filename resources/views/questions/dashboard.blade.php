@extends('layouts.questions.master')
@section('title', 'Dashboard')
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
	table 
	{
		    font-size: 11px;
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
			<p class="suggestive">Dashboard</p>
		</div>
		<div class="row">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    <h3 class="panel-title">Overview</h3>
			  </div>
			  <div class="panel-body">
			
			  	@if(empty($dashboard))
				  	<div class="row">
				  		<div class = "col-md-12">
				  			<label class="alert alert-info col-md-12">No information found for any year, @if(Auth::user()->role==2) please run <a title="run analysis" href="{{route('showTasks')}}">analysis</a> @else Please contact admin to run analysis @endif</label>  			
				  		</div>
				  	</div>
			  	@else
			  		@foreach(json_decode($dashboard) as $chartkey=>$dash)
			  			<div class="row">
			  				<hr>
			  				<div class = "col-md-12">
					  			<label class=" alert alert-info col-md-12 text text-center">Summary Report for Year {{$dash->year}} Question Database<br>
					  			<span>
											<form name="gotoQuest" class ="form-inline" method="POST" action="{{ route('showStats') }}">
												<input type="hidden" name="_token" value="{{ csrf_token() }}">
												<input type="hidden" name="taskId" value="{{$dash->taskId}}">
												<input type="hidden" name="year" value="{{$dash->year}}">
												<button style="cursor:pointer" type="submit" class="label btn-info">View detailed Analysis</button>
											</form>
										</span></label>
					  		</div>
					  		<h4 class="text text-center">Total Questions {{$dash->TotalQues}}</h4>
					  		<h5 class="text text-center"><a href="#" data-toggle="tooltip" data-placement="top" title="Accuracy is defined as how much is the predicted tagging that is the post tag is similar to the manual tagging or the pre-tag">Overall Accuracy {{$dash->accuracy}}%</a></h5>
					  		@foreach($dash->tags as $key=>$tag)
						  		<div class = " col-md-4">
						  			<h4 class="text text-center">@if($key==0)Aptitude @elseif($key==1)Electricals @else Programming @endif</h4>
						  			<div id="chart{{$chartkey}}{{$key}}" style="width: 100%; height: 450px;"></div>
						  			<table class="table">
										  <thead>
										    <tr>
												<th scope="col">Category</th>
												<th scope="col">PreTag</th>
												<th scope="col">PostTag</th>
												<th scope="col">Original Difference</th>
												<th scope="col">Current</th>
										    </tr>
										  </thead>
										  <tbody>
										  	@foreach ($tag as $Task)
										  	<tr class="text-center">
												<td scope="row">
													{{$Task->difficulty_level}}
												</td>
												<td scope="row">
													{{$Task->pre_tag}}
												</td>
												<td scope="row">
													{{$Task->post_tag}}
												</td>
												<td scope="row">
													@if($Task->diff < 0)
													<span class="label label-danger">{{$Task->diff}}</span>
													@elseif($Task->diff > 0)
													<span class="label label-danger">{{$Task->diff}}</span>
													@else
													<span class="label label-success">{{$Task->diff}}</span>
													@endif
												</td>
												<td scope="row">
													@if($Task->diff < 0)
													<span class="label label-danger">{{$Task->currentdiff}}</span>
													@elseif($Task->diff > 0)
													<span class="label label-danger">{{$Task->currentdiff}}</span>
													@else
													<span class="label label-success">{{$Task->diff}}</span>
													@currentdiff
													@endif
												</td>
										    </tr>
										  	@endforeach
										  </tbody>
									</table>
									<script type="text/javascript">
										function drawChart{{$chartkey}}{{$key}}() {
									        var data = google.visualization.arrayToDataTable([
									          ['Difficulty', 'Pre-Tag', 'Post-Tag', 'Expected Count'],
									          ['{{$tag[0]->difficulty_level}}', {{$tag[0]->pre_tag}}, {{$tag[0]->post_tag}}, {{{($dash->TotalQuess)*3/30}}} ],
									          ['{{$tag[1]->difficulty_level}}', {{$tag[1]->pre_tag}}, {{$tag[1]->post_tag}}, {{($dash->TotalQuess)*4/30}} ],
									          ['{{$tag[2]->difficulty_level}}', {{$tag[2]->pre_tag}}, {{$tag[2]->post_tag}}, {{($dash->TotalQuess)*3/30}} ]
									        ]);
									    
											var options = {
												legend: { 
												    position : 'bottom'
												  },
												vAxis: {minValue: 0},
									      		colors: ['#1b9e77', '#d95f02', '#7570b3'],
									          chart: {
									            title: 'Comparison between Machine and Manual Prediction',
									          }
									        };
									        var chart = new google.charts.Bar(document.getElementById('chart{{$chartkey}}{{$key}}'));

									        chart.draw(data, google.charts.Bar.convertOptions(options));
									    }
									</script>
						  		</div>
					  		@endforeach					  		
					  	</div>
			  		@endforeach
			  	@endif
			  </div>
			</div>
			
		</div>
	    
	</div>
@endsection
@section('scripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	$(document).ready( function() {
		$('#dashboard').addClass('active');
		$('[data-toggle="tooltip"]').tooltip(); 
		google.charts.load('current', {'packages':['bar']});
		@foreach(json_decode($dashboard) as $chartkey=>$dash)
			@foreach($dash->tags as $key=>$tag)
				google.charts.setOnLoadCallback(drawChart{{$chartkey}}{{$key}});
			@endforeach
		@endforeach
	});
</script>
@endsection