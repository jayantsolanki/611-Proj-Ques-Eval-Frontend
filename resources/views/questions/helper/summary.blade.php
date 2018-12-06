<span class="text-info text-center">Total Questions included in Exam, should be 1800 or more for set creation</span>
<table class="table table-hover">
	<thead>
		<tr>
			<th scope="col">Difficulty Level</th>
			<th scope="col">Aptitude</th>
			<th scope="col">Electricals</th>
			<th scope="col">Programming</th>
			<th scope="col">Total</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<span class="label label-info">Exp > 180</span> Easy 
			</td>
			<td>
				<span class="label @if($summary['apti'][0]['tag'] < 180) label-danger @else label-success @endif">{{$summary['apti'][0]['tag']}}</span>
			</td>
			<td>
				<span class="label @if($summary['elec'][0]['tag'] < 180) label-danger @else label-success @endif">{{$summary['elec'][0]['tag']}}</span>
			</td>
			<td>
				<span class="label @if($summary['prog'][0]['tag'] < 180) label-danger @else label-success @endif">{{$summary['prog'][0]['tag']}}</span>
			</td>
			<td>
				<span class="label @if($summary['apti'][0]['tag'] >= 180) @if($summary['elec'][0]['tag'] >= 180) @if($summary['prog'][0]['tag'] >= 180) label-success  @else label-danger @endif  @else label-danger @endif @else label-danger @endif">{{$summary['apti'][0]['tag']+$summary['elec'][0]['tag']+$summary['prog'][0]['tag']}}</span>
			</td>
		</tr>
		<tr>
			<td>
				<span class="label label-info">Exp > 240</span> Medium 
			</td>
			<td>
				<span class="label @if($summary['apti'][1]['tag'] < 240) label-danger @else label-success @endif">{{$summary['apti'][1]['tag']}}</span>
			</td>
			<td>
				<span class="label @if($summary['elec'][1]['tag'] < 240) label-danger @else label-success @endif">{{$summary['elec'][1]['tag']}}</span>
			</td>
			<td>
				<span class="label @if($summary['prog'][1]['tag'] < 240) label-danger @else label-success @endif">{{$summary['prog'][1]['tag']}}</span>
			</td>
			<td>
				<span class="label @if($summary['apti'][1]['tag'] >= 240) @if($summary['elec'][1]['tag'] >= 240) @if($summary['prog'][1]['tag'] >= 240) label-success  @else label-danger @endif  @else label-danger @endif @else label-danger @endif">{{$summary['apti'][1]['tag']+$summary['elec'][1]['tag']+$summary['prog'][1]['tag']}}</span>
			</td>
		</tr>
		<tr>
			<td>
				<span class="label label-info">Exp > 180</span> Hard 
			</td>
			<td>
				<span class="label @if($summary['apti'][2]['tag'] < 180) label-danger @else label-success @endif">{{$summary['apti'][2]['tag']}}</span>
			</td>
			<td>
				<span class="label @if($summary['elec'][2]['tag'] < 180) label-danger @else label-success @endif">{{$summary['elec'][2]['tag']}}</span>
			</td>
			<td>
				<span class="label @if($summary['prog'][2]['tag'] < 180) label-danger @else label-success @endif">{{$summary['prog'][2]['tag']}}</span>
			</td>
			<td>
				<span class="label @if($summary['apti'][2]['tag'] >= 180) @if($summary['elec'][2]['tag'] >= 180) @if($summary['prog'][2]['tag'] >= 180) label-success  @else label-danger @endif  @else label-danger @endif @else label-danger @endif">{{$summary['apti'][2]['tag']+$summary['elec'][2]['tag']+$summary['prog'][2]['tag']}}</span>
			</td>
		</tr>
		<tr>
			<td>
				<span class="label label-info">Exp > 600</span> Total 
			</td>
			<td>
				<span class="label @if($summary['apti'][0]['tag'] >= 180) @if($summary['apti'][1]['tag'] >= 240) @if($summary['apti'][2]['tag'] >= 180) label-success  @else label-danger @endif  @else label-danger @endif @else label-danger @endif">{{$summary['apti'][0]['tag']+$summary['apti'][1]['tag']+$summary['apti'][2]['tag']}}</span>
			</td>
			<td>
				<span class="label @if($summary['elec'][0]['tag'] >= 180) @if($summary['elec'][1]['tag'] >= 240) @if($summary['elec'][2]['tag'] >= 180) label-success  @else label-danger @endif  @else label-danger @endif @else label-danger @endif">{{$summary['elec'][0]['tag']+$summary['elec'][1]['tag']+$summary['elec'][2]['tag']}}</span>
			</td>
			<td>
				<span class="label @if($summary['prog'][0]['tag'] >= 180) @if($summary['prog'][1]['tag'] >= 240) @if($summary['prog'][2]['tag'] >= 180) label-success  @else label-danger @endif  @else label-danger @endif @else label-danger @endif">{{$summary['prog'][0]['tag']+$summary['prog'][1]['tag']+$summary['prog'][2]['tag']}}</span>
			</td>
			<td>
				<span class="label @if($summary['apti'][0]['tag'] >= 180) @if($summary['apti'][1]['tag'] >= 240) @if($summary['apti'][2]['tag'] >= 180) @if($summary['elec'][0]['tag'] >= 180) @if($summary['elec'][1]['tag'] >= 240) @if($summary['elec'][2]['tag'] >= 180) @if($summary['prog'][0]['tag'] >= 180) @if($summary['prog'][1]['tag'] >= 240) @if($summary['prog'][2]['tag'] >= 180) @if($summary['apti'][2]['tag'] >= 180) @if($summary['elec'][2]['tag'] >= 180) @if($summary['prog'][2]['tag'] >= 180) @if($summary['apti'][1]['tag'] >= 240) @if($summary['elec'][1]['tag'] >= 240) @if($summary['prog'][1]['tag'] >= 240) @if($summary['apti'][0]['tag'] >= 180) @if($summary['elec'][0]['tag'] >= 180) @if($summary['prog'][0]['tag'] >= 180) label-success  @else label-danger @endif  @else label-danger @endif @else label-danger @endif  @else label-danger @endif  @else label-danger @endif @else label-danger @endif  @else label-danger @endif  @else label-danger @endif @else label-danger @endif  @else label-danger @endif  @else label-danger @endif @else label-danger @endif  @else label-danger @endif  @else label-danger @endif @else label-danger @endif  @else label-danger @endif  @else label-danger @endif @else label-danger @endif">{{$summary['apti'][0]['tag']+$summary['elec'][0]['tag']+$summary['prog'][0]['tag'] + $summary['apti'][1]['tag']+$summary['elec'][1]['tag']+$summary['prog'][1]['tag'] + $summary['apti'][2]['tag']+$summary['elec'][2]['tag']+$summary['prog'][2]['tag']}}</span>
			</td>
		</tr>
	</tbody>
</table>
<p>
<span class="@if($summary['apti'][0]['tag'] >= 180) @if($summary['apti'][1]['tag'] >= 240) @if($summary['apti'][2]['tag'] >= 180) @if($summary['elec'][0]['tag'] >= 180) @if($summary['elec'][1]['tag'] >= 240) @if($summary['elec'][2]['tag'] >= 180) @if($summary['prog'][0]['tag'] >= 180) @if($summary['prog'][1]['tag'] >= 240) @if($summary['prog'][2]['tag'] >= 180) @if($summary['apti'][2]['tag'] >= 180) @if($summary['elec'][2]['tag'] >= 180) @if($summary['prog'][2]['tag'] >= 180) @if($summary['apti'][1]['tag'] >= 240) @if($summary['elec'][1]['tag'] >= 240) @if($summary['prog'][1]['tag'] >= 240) @if($summary['apti'][0]['tag'] >= 180) @if($summary['elec'][0]['tag'] >= 180) @if($summary['prog'][0]['tag'] >= 180) set-create @else hidden @endif  @else hidden @endif @else hidden @endif  @else hidden @endif  @else hidden @endif @else hidden @endif  @else hidden @endif  @else hidden @endif @else hidden @endif  @else hidden @endif  @else hidden @endif @else hidden @endif  @else hidden @endif  @else hidden @endif @else hidden @endif  @else hidden @endif  @else hidden @endif @else hidden @endif"><a onclick="setCreate('{{csrf_token()}}')" class="label label-info" id="setMessage">Click here to create new 60 sets: </a>&nbsp;<span class="setStatus hidden"> </span> <i class="setStatus hidden fa fa-refresh fa-spin"></i>
</p>