<?php

namespace App\Http\Controllers\MLAnalysis;
use Auth;
// use App\UserDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\QuestionMaster;
use App\Login;
use App\DatabaseCatalogue;
use App\Analysis;
use App\Stats;
use App\UserDetails;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
// use DB;

class QAnalysisController extends Controller{

 	public function createFeatures(Request $data){
 		$inactiveUsers = Login::where('active',0)->count();
 		$years = DatabaseCatalogue::get()->pluck('year');
 		$runningTask = Analysis::where('status',1)->count();
 		$Tasks = Analysis::get();
 		if(Auth::user()->role != 2){//only allowed for admin
 			return view('questions.taskViewer')->with('error', "You cannot run another analysis until the previous analysis is finished")->with('userDetails', Auth::user())->with('hasFeatures', 0)->with('defaultyear', 2017)->with('inactiveUsers', $inactiveUsers)->with('years', $years)->with('runningTask', $runningTask)->with('Tasks', $Tasks);
 		}
 		
 		if(sizeof($data->all())>0)
 		{

 			$Tasks = Analysis::get();
 			if($runningTask>0){
 				return view('questions.taskViewer')->with('error', "You cannot run another analysis until the previous analysis is finished")->with('userDetails', Auth::user())->with('inactiveUsers', $inactiveUsers)->with('hasFeatures', 0)->with('defaultyear', 2017)->with('years', $years)->with('runningTask', $runningTask)->with('Tasks', $Tasks);
 			}
 			$rules = [
			            'year' => 'required|integer|min:2014'
			        ];
			$messages = [   
			            'year.required' =>  'Database year name is required',
			            'year.integer' =>  'Database year must be integer',
			            'year.min' =>  'Database year must be at least 2014 or above'
			        ];
			$validator = Validator::make($data->all(), $rules, $messages);
			if ($validator->fails()) {
	            return view('questions.taskViewer')->withErrors($validator)->with('userDetails', Auth::user())->with('inactiveUsers', $inactiveUsers)->with('hasFeatures', 0)->with('runningTask', $runningTask)->with('defaultyear', 2017)->with('years', $years)->with('Tasks', $Tasks);
	        }
	        $dbdetails = DatabaseCatalogue::where('year',$data->year)->first();//fetching details of the database with year value
 			//drop these tables if they exists
 			
	 		DB::statement("drop table if exists WeightedFeature1;");
	 		DB::statement("drop table if exists WeightedFeature2;");
	 		DB::statement("drop table if exists w_values;");
	 		DB::statement("drop table if exists WeightedFeatures;");
	 		// Create the table for weight values
	 		DB::statement("create table w_values 
				(
				test_id INT(11) PRIMARY KEY,
				w FLOAT(7, 5)
				);");
	 		//-- Calculate w values for each test id
			DB::statement("set @alpha = 1;");

			DB::statement("insert into w_values
				select distinct id, 2/(1 + exp(@alpha * (avgMarks - marks_scored) / stdMarks)) as w
				from `".$dbdetails->dbName."`.test_start_details, 
				(
				select avg(marks_scored) as avgMarks, std(marks_scored) as stdMarks
				from `".$dbdetails->dbName."`.test_start_details
				) as T;");

			//--Calculate weighted Feature 1: Fraction of People who solved the given question correctly
			DB::statement("create table WeightedFeature1 (question_id INT(11) PRIMARY KEY, feature1 FLOAT(7, 5));");
			DB::statement("insert into WeightedFeature1
				select B.question_id, sum(w) / Total as feature1
				from `".$dbdetails->dbName."`.test_ques_ans_dtls B join 
					(
						select question_id, sum(w) as Total
				        from `".$dbdetails->dbName."`.test_ques_ans_dtls T1 join w_values T2 on (T1.test_id = T2.test_id)
				        group by question_id
					) as T on(B.question_id = T.question_id) join
				    w_values on (B.test_id = w_values.test_id)
				where marks = 3
				group by question_id
				order by question_id;
				");

			//--Calculate Weighted Feature 2: Average Marks of People who did not attempt the question or solved it incorrectly
			DB::statement("create table WeightedFeature2 (question_id INT(11) PRIMARY KEY, feature2 FLOAT(7, 5));");

			DB::statement("insert into WeightedFeature2
				select question_id, sum(marks_scored * w) / sum(w) as feature2
				from `".$dbdetails->dbName."`.test_start_details A join `".$dbdetails->dbName."`.test_ques_ans_dtls B on(A.id = B.test_id) join w_values C on(B.test_id = C.test_id)
				where (marks = -1 or marks = 0)
				group by question_id
				order by question_id;");

			//-- Calculate Weighted Features
			DB::statement("create table WeightedFeatures (question_id INT(11) PRIMARY KEY, feature1 FLOAT(7, 5), feature2 FLOAT(7, 5), difficulty_level INT(1));");
			DB::statement("insert into WeightedFeatures
				select *
				from WeightedFeature1 natural join WeightedFeature2 natural join (select id as question_id, difficulty_level from `".$dbdetails->dbName."`.question_master) as T
				order by question_id;");
			//creating new task
			$newTask = new Analysis;
			$newTask -> year =  $dbdetails->year;
			$newTask -> progress =  0;
			$newTask -> accuracy =  0;
			$newTask -> status =  0;
			$newTask -> algoUsed =   'Weighted K-Means Clustering';
            $newTask -> creator =  Auth::user()->email;
            $newTask->save();
			// DB::statement("insert into Analysis
			// 	VALUES (".$dbdetails->year.", 0, 0, 'Weighted K-Means Clustering', 0);");
			//-- Remove Redundant Tables
			DB::statement("drop table if exists WeightedFeature1;");
	 		DB::statement("drop table if exists WeightedFeature2;");
	 		DB::statement("drop table if exists w_values;");
	 		$Tasks = Analysis::get();
	 		return view('questions.taskViewer')->with('userDetails', Auth::user())->with('hasFeatures', 1)->with('defaultyear', $dbdetails->year)->with('inactiveUsers', $inactiveUsers)->with('years', $years)->with('runningTask', $runningTask)->with('Tasks', $Tasks)->with('taskId', $newTask->id);


 		}
 		

 		// return default if no request asked
 		return 0;
 		return view('questions.taskViewer')->with('userDetails', Auth::user())->with('hasFeatures', 0)->with('defaultyear', 2017)->with('inactiveUsers', $inactiveUsers)->with('years', $years)->with('runningTask', $runningTask)->with('Tasks', $Tasks);
 	}
 	public function showDashboard(Request $data){
 		if(Auth::user()->role != 2 && Auth::user()->role != 1){
 			return redirect()->route('login')->with('error', 'Unauthorised Access');
 		}
 		$inactiveUsers = Login::where('active',0)->count();
 		$userdetails = UserDetails::where('id',Auth::user()->user_id)->first();
 		$years = DatabaseCatalogue::get()->pluck('year');
 		
		//////////////////////////////////////////////////////////
		// $summary = $this->retrieveStats($data->year, $TotalQues);
		$dashboard = [];
 		foreach ($years as $year) {
 			if($year==2018)
 				break;
 			$TotalQues = QuestionMaster::where('year',$year)->count();
			$summary = $this->retrieveStats($year, $TotalQues);
			$Analysis = Analysis::where('year', $year)->where('status', 2)->first();
			$stats = array(
				'year' => $year,
				'tags' => $summary,
				'taskId' => $Analysis->id,
				'accuracy' => $Analysis->accuracy,
				'algoUsed' => $Analysis->algoUsed,
				'TotalQues' => $TotalQues

			);
			array_push($dashboard,$stats);

 		}
 		// foreach ($dashboard[0]['tags'] as $year) {
 		// 	return $year;
 		// }
 		// return $dashboard[0];
 		return view('questions.dashboard')->with('userDetails', $userdetails)->with('inactiveUsers', $inactiveUsers)->with('dashboard', json_encode($dashboard));
 	}

 	public function showStats(Request $data){
 		// if(Auth::user()->role != 2){
 		// 	return redirect()->route('login')->with('error', 'Unauthorised Access');
 		// }
 		$inactiveUsers = Login::where('active',0)->count();
 		$years = DatabaseCatalogue::get()->pluck('year');
 		if(sizeof($data->all())>0)
 		{
 			$rules = [
			            'taskId' => 'integer|min:1',
			            'year' => 'required|integer|min:2014'
			        ];
			$messages = [   
			            'year.required' =>  'Year is required',
			            'taskId.integer' =>  'Task Id must be integer',
			            'taskId.min' =>  'Task Id must be at least 1 or above'
			        ];
			$validator = Validator::make($data->all(), $rules, $messages);
			if ($validator->fails()) {
	            return redirect()->route('showStats')->withErrors($validator);
	        }
 			
 			if(isset($data->taskId))
 			{
 				$TotalQues = QuestionMaster::where('year',$data->year)->count();
 				$Reports = Analysis::where('year', $data->year)->where('status',2)->get();
 				$Stats = Stats::where('task_id', $data->taskId)->get();
 				//////////////////////////////////////////////////////////
 				$summary = $this->retrieveStats($data->year, $TotalQues);
 				$apti = json_encode($summary[0]);
 				$elec = json_encode($summary[1]);
 				$prog = json_encode($summary[2]);
 				///////////////////////////////////////////////////////////
 			
 				return view('questions.showStat')->with('userDetails', Auth::user())->with('inactiveUsers', $inactiveUsers)->with('years', $years)->with('defaultyear', $data->year)->with('TotalQues', $TotalQues)->with('Reports', $Reports)->with('Stats', $Stats)->with('apti',$apti)->with('elec',$elec)->with('prog',$prog);


 			}
 			else{
 				$TotalQues = QuestionMaster::where('year',$data->year)->count();
 				$Reports = Analysis::where('year', $data->year)->where('status',2)->get();
 				if(sizeof($Reports)>0)
		 		{
		 			return view('questions.showStat')->with('userDetails', Auth::user())->with('inactiveUsers', $inactiveUsers)->with('years', $years)->with('defaultyear', $data->year)->with('TotalQues', $TotalQues)->with('Reports', $Reports);

		 		}
		 		else{
		 			return view('questions.showStat')->with('error',"No reports found for Year ".$data->year."")->with('userDetails', Auth::user())->with('inactiveUsers', $inactiveUsers)->with('years', $years)->with('defaultyear', $data->year)->with('TotalQues', $TotalQues)->with('Reports', []);
		 		}
 			}



 		}
 		$TotalQues = QuestionMaster::where('year',2017)->count();
 		$Reports = Analysis::where('year', 2017)->where('status',2)->get();
 		if(sizeof($Reports)>0)
 		{
 			return view('questions.showStat')->with('userDetails', Auth::user())->with('inactiveUsers', $inactiveUsers)->with('years', $years)->with('defaultyear', 2017)->with('TotalQues', $TotalQues)->with('Reports', $Reports);

 		}
 		else{
 			return view('questions.showStat')->with('userDetails', Auth::user())->with('inactiveUsers', $inactiveUsers)->with('years', $years)->with('defaultyear', 2017)->with('TotalQues', $TotalQues)->with('Reports', []);
 		}
 		
 	}

 	// view tasks and create one
 	public function showTasks(Request $data){
 		// if(Auth::user()->role != 2){
 		// 	return redirect()->route('login')->with('error', 'Unauthorised Access');
 		// }
 		$runningTask = Analysis::where('status',0)->count();
 		$Tasks = Analysis::get();
 		$inactiveUsers = Login::where('active',0)->count();
 		if(sizeof($data->all())>0)//just for deleting a rogue task
 		{
 			$rules = [
			            'id' => 'required|integer|min:1'
			        ];
			$messages = [   
			            'id.required' =>  'Task Id is required',
			            'id.integer' =>  'Task Id must be integer',
			            'id.min' =>  'Task Id must be at least 1 or above'
			        ];
			$validator = Validator::make($data->all(), $rules, $messages);
			if ($validator->fails()) {
	            return redirect()->route('showTasks')->withErrors($validator);
	        }
 			$Analysis = Analysis::where('id', $data->id)->first();
 			if(sizeof($Analysis)==0){
 				return redirect()->route('showTasks')->with('error', "Enter a valid Task Id");
 			}
 			// return $Analysis->status;
 			if($Analysis->status > 0){
 				return redirect()->route('showTasks')->with('error', "Only dormant tasks can be deleted, not the ongoing or completed tasks");
 			}
 			else{
	 			$Analysis->delete();
	 			$Tasks = Analysis::get();
	 			return redirect()->route('showTasks')->with('success', "Task deleted");
	 		}
 		}
 		//fetching database catalogues
 		$years = DatabaseCatalogue::get()->pluck('year');
 		return view('questions.taskViewer')->with('userDetails', Auth::user())->with('inactiveUsers', $inactiveUsers)->with('years', $years)->with('defaultyear', 2017)->with('hasFeatures', 0)->with('runningTask', $runningTask)->with('Tasks', $Tasks);
 	}

 	public function checkProgress(Request $data){
 		// if(Auth::user()->role != 2){
 		// 	return redirect()->route('login')->with('error', 'Unauthorised Access');
 		// }
 		
 		$Analysis = Analysis::orderBy('id', 'desc')->first();//check for recent ongoing task
 		if(sizeof($Analysis)>0){
 			return $Analysis;
 		}
 		else
 			return 0;
 	}

 	private function retrieveStats($year, $TotalQues){

 		$QuesStatsPreEasy = QuestionMaster::where('year',$year)->where('category_id', 1)->where('pre_tag',0)->count();//apti, pre tag easy
			$QuesStatsPreMed = QuestionMaster::where('year',$year)->where('category_id', 1)->where('pre_tag',1)->count();//apti, pre tag medium
			$QuesStatsPreHard = QuestionMaster::where('year',$year)->where('category_id', 1)->where('pre_tag',2)->count(); //apti, pre tag hard
			///////////
			$QuesStatsPostEasy = QuestionMaster::where('year',$year)->where('category_id', 1)->where('post_tag',0)->count();//apti, post_tag tag easy
			$QuesStatsPostMed = QuestionMaster::where('year',$year)->where('category_id', 1)->where('post_tag',1)->count();//apti, post_tag tag med
			$QuesStatsPostHard = QuestionMaster::where('year',$year)->where('category_id', 1)->where('post_tag',2)->count();//apti, post_tag tag hard
			/////////////
			$apti = [];
			$payload = array(
				'difficulty_level'=>'easy',
				'pre_tag'=> $QuesStatsPreEasy,
				'post_tag'=> $QuesStatsPostEasy,
				'diff' => $TotalQues*3/30-$QuesStatsPostEasy

			);
			array_push($apti, $payload);
			$payload = array(
				'difficulty_level'=>'medium',
				'pre_tag'=> $QuesStatsPreMed,
				'post_tag'=> $QuesStatsPostMed,
				'diff' => $TotalQues*4/30-$QuesStatsPostMed

			);
			array_push($apti, $payload);
			$payload = array(
				'difficulty_level'=>'hard',
				'pre_tag'=> $QuesStatsPreHard,
				'post_tag'=> $QuesStatsPostHard,
				'diff' => $TotalQues*3/30-$QuesStatsPostHard

			);
			array_push($apti, $payload);
			// $apti = json_encode($apti);
			// return $a/pti;
			///////////////////////////////////////////////////////////
			$QuesStatsPreEasy = QuestionMaster::where('year',$year)->where('category_id', 2)->where('pre_tag',0)->count();//electricals, pre tag easy
			$QuesStatsPreMed = QuestionMaster::where('year',$year)->where('category_id', 2)->where('pre_tag',1)->count();//electricals, pre tag medium
			$QuesStatsPreHard = QuestionMaster::where('year',$year)->where('category_id', 2)->where('pre_tag',2)->count(); //electricals, pre tag hard
			///////////
			$QuesStatsPostEasy = QuestionMaster::where('year',$year)->where('category_id', 2)->where('post_tag',0)->count();//electricals, post_tag tag easy
			$QuesStatsPostMed = QuestionMaster::where('year',$year)->where('category_id', 2)->where('post_tag',1)->count();//electricals, post_tag tag med
			$QuesStatsPostHard = QuestionMaster::where('year',$year)->where('category_id', 2)->where('post_tag',2)->count();//electricals, post_tag tag hard
			/////////////
			$elec = [];
			$payload = array(
				'difficulty_level'=>'easy',
				'pre_tag'=> $QuesStatsPreEasy,
				'post_tag'=> $QuesStatsPostEasy,
				'diff' => $TotalQues*3/30-$QuesStatsPostEasy

			);
			array_push($elec, $payload);
			$payload = array(
				'difficulty_level'=>'medium',
				'pre_tag'=> $QuesStatsPreMed,
				'post_tag'=> $QuesStatsPostMed,
				'diff' => $TotalQues*4/30-$QuesStatsPostMed

			);
			array_push($elec, $payload);
			$payload = array(
				'difficulty_level'=>'hard',
				'pre_tag'=> $QuesStatsPreHard,
				'post_tag'=> $QuesStatsPostHard,
				'diff' => $TotalQues*3/30-$QuesStatsPostHard

			);
			array_push($elec, $payload);
			// $elec = json_encode($elec);
			// return $elec;
			///////////////////////////////////////////////////////////
			$QuesStatsPreEasy = QuestionMaster::where('year',$year)->where('category_id', 3)->where('pre_tag',0)->count();//programming, pre tag easy
			$QuesStatsPreMed = QuestionMaster::where('year',$year)->where('category_id', 3)->where('pre_tag',1)->count();//programming, pre tag medium
			$QuesStatsPreHard = QuestionMaster::where('year',$year)->where('category_id', 3)->where('pre_tag',2)->count(); //programming, pre tag hard
			///////////
			$QuesStatsPostEasy = QuestionMaster::where('year',$year)->where('category_id', 3)->where('post_tag',0)->count();//programming, post_tag tag easy
			$QuesStatsPostMed = QuestionMaster::where('year',$year)->where('category_id', 3)->where('post_tag',1)->count();//programming, post_tag tag med
			$QuesStatsPostHard = QuestionMaster::where('year',$year)->where('category_id', 3)->where('post_tag',2)->count();//programming, post_tag tag hard
			/////////////
			$prog = [];
			$payload = array(
				'difficulty_level'=>'easy',
				'pre_tag'=> $QuesStatsPreEasy,
				'post_tag'=> $QuesStatsPostEasy,
				'diff' => $TotalQues*3/30-$QuesStatsPostEasy

			);
			array_push($prog, $payload);
			$payload = array(
				'difficulty_level'=>'medium',
				'pre_tag'=> $QuesStatsPreMed,
				'post_tag'=> $QuesStatsPostMed,
				'diff' => $TotalQues*4/30-$QuesStatsPostMed

			);
			array_push($prog, $payload);
			$payload = array(
				'difficulty_level'=>'hard',
				'pre_tag'=> $QuesStatsPreHard,
				'post_tag'=> $QuesStatsPostHard,
				'diff' => $TotalQues*3/30-$QuesStatsPostHard

			);
			array_push($prog, $payload);
			// $prog = json_encode($prog);

			return array($apti, $elec, $prog);

 	}
}
