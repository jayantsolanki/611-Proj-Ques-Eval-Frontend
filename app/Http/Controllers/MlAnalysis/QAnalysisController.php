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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
// use DB;

class QAnalysisController extends Controller{

 	public function createFeatures(Request $data){
 		if(Auth::user()->role != 2){//only allowed for admin
 			return redirect()->route('login')->with('error', 'Unauthorised Access');
 		}
 		$inactiveUsers = Login::where('active',0)->count();
 		$years = DatabaseCatalogue::get()->pluck('year');
 		$runningTask = Analysis::where('status',0)->count();
 		
 		if($data->all()>0)
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
			            'year.integer' =>  'Database yea must be integer',
			            'year.min' =>  'Database year must be at least 2014 or above'
			        ];
			$validator = Validator::make($data->all(), $rules, $messages);
			if ($validator->fails()) {
	            return view('questions.taskViewer')->withErrors($validator)->with('userDetails', Auth::user())->with('inactiveUsers', $inactiveUsers)->with('hasFeatures', 0)->with('runningTask', $runningTask)->with('defaultyear', 2017)->with('years', $years)->with('Tasks', $Tasks);
	        }
	        $dbdetails = DatabaseCatalogue::where('year',$data->year)->first();//fetching details of the database with year value
 			//drop these tables if they exists
 			$Tasks = Analysis::get();
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

	 		return view('questions.taskViewer')->with('userDetails', Auth::user())->with('hasFeatures', 1)->with('defaultyear', $dbdetails->year)->with('inactiveUsers', $inactiveUsers)->with('years', $years)->with('runningTask', $runningTask)->with('Tasks', $Tasks)->with('taskId', $newTask->id);


 		}
 		

 		// reutrn default if no request asked
 		return view('questions.taskViewer')->with('userDetails', Auth::user())->with('hasFeatures', 0)->with('defaultyear', 2017)->with('inactiveUsers', $inactiveUsers)->with('years', $years)->with('runningTask', $runningTask)->with('Tasks', $Tasks);
 	}
 	public function createTask(Request $data){
 		if(Auth::user()->role != 2){
 			return redirect()->route('login')->with('error', 'Unauthorised Access');
 		}
 		$userdetails = UserDetails::where('id',Auth::user()->user_id)->first();
 		return view('members.profile')->with('userDetails', $userdetails);
 	}
 	public function checkProgress(Request $data){
 		if(Auth::user()->role != 2){
 			return redirect()->route('login')->with('error', 'Unauthorised Access');
 		}
 		$userdetails = UserDetails::where('id',Auth::user()->user_id)->first();
 		return view('members.editProfile')->with('userDetails', $userdetails);
 	}

 	public function showStats(){
 		if(Auth::user()->role != 2){
 			return redirect()->route('login')->with('error', 'Unauthorised Access');
 		}
 		$inactiveUsers = Login::where('active',0)->count();
 		return view('questions.showStat')->with('userDetails', Auth::user())->with('inactiveUsers', $inactiveUsers);
 	}
 	public function showTasks(){
 		if(Auth::user()->role != 2){
 			return redirect()->route('login')->with('error', 'Unauthorised Access');
 		}
 		$runningTask = Analysis::where('status',0)->count();
 		$Tasks = Analysis::get();
 		$inactiveUsers = Login::where('active',0)->count();
 		//fetching database catalogues
 		$years = DatabaseCatalogue::get()->pluck('year');
 		return view('questions.taskViewer')->with('userDetails', Auth::user())->with('inactiveUsers', $inactiveUsers)->with('years', $years)->with('defaultyear', 2017)->with('hasFeatures', 0)->with('runningTask', $runningTask)->with('Tasks', $Tasks);
 	}
}
