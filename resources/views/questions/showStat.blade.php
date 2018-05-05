@extends('layouts.questions.master')
@section('title', 'Statistics')
@section('styles')
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
		.badge-info {
        background-color: #46b8da;
      }
          .axis path, .axis line
        {
            fill: none;
            stroke: #777;
            shape-rendering: crispEdges;
        }
        
        .axis text
        {
            font-family: 'Arial';
            font-size: 10px;
        }
        .tick
        {
            stroke-dasharray: 1, 2;
        }
        .bar
        {
            fill: FireBrick;
        }
        
       
	</style>
@stop

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
			<p class="suggestive"><span class=""></span>Analysis information of Question Database</p>
		</div>
		<div class="row">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    <h3 class="panel-title">Choose year to view the information</h3>
			  </div>
			  <div class="panel-body">
			  	<div class="row">
			  		<div class = "col-md-12">
			  			<label>Select year</label>
			  			<form method="POST" action="{{ route('showStats') }}">
					        <input type="hidden" name="_token" value="{{ csrf_token() }}">
					        <div class="form-group input-group">
					            <select class="form-control" name="year" id="year">
					            	<option value="" disabled selected>Choose year</option>
					            	@foreach ($years as $year)
					            		<option value="{{$year}}" @if($year == $defaultyear) selected @endif>{{$year}}</option>
					            	@endforeach
								</select>
					        </div>
					        <div class="form-group">
					            <button style="cursor:pointer" type="submit" class="btn btn-info pull-right">View Analysis Info</button>
					        </div>
					    </form>
			  		</div>
			  	</div>
			  	@if(sizeof($Reports) == 0)
			  	<div class="row">
			  		<hr>
			  		<div class = " col-md-12">
			  			<label class=" alert alert-info col-md-12">No information found for Year {{$defaultyear}} Question Database, please run <a title="run analysis" href="{{route('showTasks')}}">analysis</a></label>
			  		</div>
			  	</div>
			  	@else
			  	<div class="row">
			  		<hr>
			  		<div class = "alert alert-info col-md-12 text text-center">
			  			<label>List of analysis performed on Year {{$defaultyear}} Question Database</label>
			  		</div>
			  	</div>
			  	<div class="row">
			  		<hr>
			  		
			  		<div class = "col-md-12">
			  			<table class="table">
							  <thead>
							    <tr>
									<th scope="col">TaskId</th>
									<th scope="col">DB Year</th>
									<th scope="col">Accuracy %</th>
									<th scope="col">Progress %</th>
									<th scope="col">Algorithm</th>
									<th scope="col">Info</th>
									<th scope="col">Created by</th>
									<th scope="col">Last Updated</th>
							    </tr>
							  </thead>
							  <tbody>
							  	@foreach ($Reports as $Task)
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
										<span id="status{{$Task->id}}">
											<form name="gotoQuest" class ="form-inline" method="POST" action="{{ route('showStats') }}">
												<input type="hidden" name="_token" value="{{ csrf_token() }}">
												<input type="hidden" name="taskId" value="{{$Task->id}}">
												<input type="hidden" name="year" value="{{$defaultyear}}">
												<button style="cursor:pointer" type="submit" class="label btn-info">View Info</button>
											</form>
										</span>
										@endif
									</td>
									<td>
										{{$Task->creator}}
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
			  	@endif
			  	@if(isset($Stats))
			  	<div class="row">
			  		<hr>
			  		<div class = " col-md-12">
			  			<label class=" alert alert-info col-md-12 text text-center">Detailed Analysis Report for Year {{$defaultyear}} Question Database</label>
			  		</div>
			  		<h4 class="text text-center">Total Questions {{$TotalQues}}</h4>
			  		<div class = " col-md-4">
			  			<h4 class="text text-center">Aptitude Category</h4>
			  			<svg id="apti" width="300" height="500"></svg>
			  			<table class="table">
							  <thead>
							    <tr>
									<th scope="col">Difficulty Level</th>
									<th scope="col">Pre-Tag</th>
									<th scope="col">Post-Tag</th>
									<th scope="col">Difference</th>
							    </tr>
							  </thead>
							  <tbody>
							  	@foreach (json_decode($apti) as $Task)
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
							    </tr>
							  	@endforeach
							  </tbody>
						</table>
			  		</div>
			  		<div class = " col-md-4">
			  			<h4 class="text text-center">Electricals Category</h4>
			  			<svg id="elec" width="300" height="500"></svg>
			  			<table class="table">
							  <thead>
							    <tr>
									<th scope="col">Difficulty Level</th>
									<th scope="col">Pre-Tag</th>
									<th scope="col">Post-Tag</th>
									<th scope="col">Difference</th>
							    </tr>
							  </thead>
							  <tbody>
							  	@foreach (json_decode($elec) as $Task)
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
							    </tr>
							  	@endforeach
							  </tbody>
						</table>
			  		</div>
			  		<div class = " col-md-4">
			  			<h4 class="text text-center">Programming Category</h4>
			  			<svg id="prog" width="300" height="500"></svg>
			  			<table class="table">
							  <thead>
							    <tr>
									<th scope="col">Difficulty Level</th>
									<th scope="col">Pre-Tag</th>
									<th scope="col">Post-Tag</th>
									<th scope="col">Difference</th>
							    </tr>
							  </thead>
							  <tbody>
							  	@foreach (json_decode($prog) as $Task)
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
							    </tr>
							  	@endforeach
							  </tbody>
						</table>
			  		</div>
			  	</div>
			  	<hr>
			  	@foreach ($Stats as $Stat)
			  	<div class="row">
			  		
			  		<div class = "col-md-10 col-md-offset-1">
			  			<img src="/img/MLanalysis/{{$defaultyear}}/{{$Stat->img_path}}" width= "98%" height="98%">
			  		</div>
			  		
			  	</div>
			  	@endforeach
			  	@endif
	  	
			  </div>
			</div>
			
		</div>

	    
	</div>

@endsection
@section('scripts')
<script src="https://d3js.org/d3.v3.min.js"></script>
<script type="text/javascript">
	$(document).ready( function() {
		$('#st').addClass('active');
		@if(isset($Stats))
		InitChartApti();
		InitChartElec();
		InitChartProg();
		@endif

		
	});
	@if(isset($Stats))
	function InitChartApti() {

		  var barData = [{
		    'x': '{{json_decode($apti)[0]->difficulty_level}}-pre',
		    'y': {{json_decode($apti)[0]->pre_tag}},
		    "color" : "green" 
		  }, {
		    'x': '{{json_decode($apti)[0]->difficulty_level}}-post',
		    'y': {{json_decode($apti)[0]->post_tag}},
		    "color" : "green" 
		  }, {
		    'x': '{{json_decode($apti)[1]->difficulty_level}}-pre',
		    'y': {{json_decode($apti)[0]->pre_tag}},
		    "color" : "navy" 
		  }, {
		    'x': '{{json_decode($apti)[1]->difficulty_level}}-post',
		    'y': {{json_decode($apti)[0]->post_tag}},
		    "color" : "navy" 
		  },
		  {
		    'x': '{{json_decode($apti)[2]->difficulty_level}}-pre',
		    'y': {{json_decode($apti)[0]->pre_tag}},
		    "color" : "gray" 
		  }, {
		    'x': '{{json_decode($apti)[2]->difficulty_level}}-post',
		    'y': {{json_decode($apti)[0]->post_tag}},
		    "color" : "gray" 
		  },];

		  var vis = d3.select('#apti'),
		    WIDTH = 300,
		    HEIGHT = 500,
		    MARGINS = {
		      top: 20,
		      right: 20,
		      bottom: 50,
		      left: 50
		    },
		    xRange = d3.scale.ordinal().rangeRoundBands([MARGINS.left, WIDTH - MARGINS.right], 0.5).domain(barData.map(function (d) {
		      return d.x;
		    })),


		    yRange = d3.scale.linear().range([HEIGHT - MARGINS.top, MARGINS.bottom]).domain([0,
		      d3.max(barData, function (d) {
		        return d.y+20;
		      })
		    ]),

		    xAxis = d3.svg.axis()
		      .scale(xRange)
		      .tickSize(5)
		      .tickSubdivide(true),

		    yAxis = d3.svg.axis()
		      .scale(yRange)
		      .tickSize(5)
		      .orient("left")
		      .tickSubdivide(true);


		  vis.append('svg:g')
		    .attr('class', 'x axis')
		    .attr('transform', 'translate(0,' + (HEIGHT - MARGINS.bottom) + ')')
		    .call(xAxis);

		  vis.append('svg:g')
		    .attr('class', 'y axis')
		    .attr('transform', 'translate(' + (MARGINS.left) + ',0)')
		    .call(yAxis);

		  vis.selectAll('rect')
		    .data(barData)
		    .enter()
		    .append('rect')
		    .attr('x', function (d) {
		      return xRange(d.x);
		    })
		    .attr('y', function (d) {
		      return yRange(d.y);
		    })
		    .attr('width', xRange.rangeBand())
		    .attr('height', function (d) {
		      return ((HEIGHT - MARGINS.bottom) - yRange(d.y));
		    })
		    .attr('fill', function(d) { return d.color; });
		    vis.selectAll("text")
		    .attr("y", 0)
		    .attr("x", 9)
		    .attr("dy", ".35em")
		    .attr("transform", "rotate(45)")
		    .style("text-anchor", "start");
	}
	function InitChartElec() {

	  var barData = [{
	    'x': '{{json_decode($elec)[0]->difficulty_level}}-pre',
	    'y': {{json_decode($elec)[0]->pre_tag}},
	    "color" : "green" 
	  }, {
	    'x': '{{json_decode($elec)[0]->difficulty_level}}-post',
	    'y': {{json_decode($elec)[0]->post_tag}},
	    "color" : "green" 
	  }, {
	    'x': '{{json_decode($elec)[1]->difficulty_level}}-pre',
	    'y': {{json_decode($elec)[0]->pre_tag}},
	    "color" : "navy" 
	  }, {
	    'x': '{{json_decode($elec)[1]->difficulty_level}}-post',
	    'y': {{json_decode($elec)[0]->post_tag}},
	    "color" : "navy" 
	  },
	  {
	    'x': '{{json_decode($elec)[2]->difficulty_level}}-pre',
	    'y': {{json_decode($elec)[0]->pre_tag}},
	    "color" : "gray" 
	  }, {
	    'x': '{{json_decode($elec)[2]->difficulty_level}}-post',
	    'y': {{json_decode($elec)[0]->post_tag}},
	    "color" : "gray" 
	  },];

	  var vis = d3.select('#elec'),
	    WIDTH = 300,
	    HEIGHT = 500,
	    MARGINS = {
	      top: 20,
	      right: 20,
	      bottom: 50,
	      left: 50
	    },
	    xRange = d3.scale.ordinal().rangeRoundBands([MARGINS.left, WIDTH - MARGINS.right], 0.5).domain(barData.map(function (d) {
	      return d.x;
	    })),


	    yRange = d3.scale.linear().range([HEIGHT - MARGINS.top, MARGINS.bottom]).domain([0,
	      d3.max(barData, function (d) {
	        return d.y+20;
	      })
	    ]),

	    xAxis = d3.svg.axis()
	      .scale(xRange)
	      .tickSize(5)
	      .tickSubdivide(true),

	    yAxis = d3.svg.axis()
	      .scale(yRange)
	      .tickSize(5)
	      .orient("left")
	      .tickSubdivide(true);


	  vis.append('svg:g')
	    .attr('class', 'x axis')
	    .attr('transform', 'translate(0,' + (HEIGHT - MARGINS.bottom) + ')')
	    .call(xAxis);

	  vis.append('svg:g')
	    .attr('class', 'y axis')
	    .attr('transform', 'translate(' + (MARGINS.left) + ',0)')
	    .call(yAxis);

	  vis.selectAll('rect')
	    .data(barData)
	    .enter()
	    .append('rect')
	    .attr('x', function (d) {
	      return xRange(d.x);
	    })
	    .attr('y', function (d) {
	      return yRange(d.y);
	    })
	    .attr('width', xRange.rangeBand())
	    .attr('height', function (d) {
	      return ((HEIGHT - MARGINS.bottom) - yRange(d.y));
	    })
	    .attr('fill', function(d) { return d.color; });
	    vis.selectAll("text")
	    .attr("y", 0)
	    .attr("x", 9)
	    .attr("dy", ".35em")
	    .attr("transform", "rotate(45)")
	    .style("text-anchor", "start");
	}
	function InitChartProg() {

	  var barData = [{
	    'x': '{{json_decode($prog)[0]->difficulty_level}}-pre',
	    'y': {{json_decode($prog)[0]->pre_tag}},
	    "color" : "green" 
	  }, {
	    'x': '{{json_decode($apti)[0]->difficulty_level}}-post',
	    'y': {{json_decode($prog)[0]->post_tag}},
	    "color" : "green" 
	  }, {
	    'x': '{{json_decode($apti)[1]->difficulty_level}}-pre',
	    'y': {{json_decode($prog)[0]->pre_tag}},
	    "color" : "navy" 
	  }, {
	    'x': '{{json_decode($apti)[1]->difficulty_level}}-post',
	    'y': {{json_decode($prog)[0]->post_tag}},
	    "color" : "navy" 
	  },
	  {
	    'x': '{{json_decode($apti)[2]->difficulty_level}}-pre',
	    'y': {{json_decode($prog)[0]->pre_tag}},
	    "color" : "gray" 
	  }, {
	    'x': '{{json_decode($apti)[2]->difficulty_level}}-post',
	    'y': {{json_decode($prog)[0]->post_tag}},
	    "color" : "gray" 
	  },];

	  var vis = d3.select('#prog'),
	    WIDTH = 300,
	    HEIGHT = 500,
	    MARGINS = {
	      top: 20,
	      right: 20,
	      bottom: 50,
	      left: 50
	    },
	    xRange = d3.scale.ordinal().rangeRoundBands([MARGINS.left, WIDTH - MARGINS.right], 0.5).domain(barData.map(function (d) {
	      return d.x;
	    })),


	    yRange = d3.scale.linear().range([HEIGHT - MARGINS.top, MARGINS.bottom]).domain([0,
	      d3.max(barData, function (d) {
	        return d.y+20;
	      })
	    ]),

	    xAxis = d3.svg.axis()
	      .scale(xRange)
	      .tickSize(5)
	      .tickSubdivide(true),

	    yAxis = d3.svg.axis()
	      .scale(yRange)
	      .tickSize(5)
	      .orient("left")
	      .tickSubdivide(true);


	  vis.append('svg:g')
	    .attr('class', 'x axis')
	    .attr('transform', 'translate(0,' + (HEIGHT - MARGINS.bottom) + ')')
	    .call(xAxis);

	  vis.append('svg:g')
	    .attr('class', 'y axis')
	    .attr('transform', 'translate(' + (MARGINS.left) + ',0)')
	    .call(yAxis);

	  vis.selectAll('rect')
	    .data(barData)
	    .enter()
	    .append('rect')
	    .attr('x', function (d) {
	      return xRange(d.x);
	    })
	    .attr('y', function (d) {
	      return yRange(d.y);
	    })
	    .attr('width', xRange.rangeBand())
	    .attr('height', function (d) {
	      return ((HEIGHT - MARGINS.bottom) - yRange(d.y));
	    })
	    .attr('fill', function(d) { return d.color; });
	    vis.selectAll("text")
	    .attr("y", 0)
	    .attr("x", 9)
	    .attr("dy", ".35em")
	    .attr("transform", "rotate(45)")
	    .style("text-anchor", "start");
	}
	@endif
</script>
@endsection