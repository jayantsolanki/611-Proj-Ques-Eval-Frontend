<?php

namespace App\Http\Controllers\MLAnalysis;
use Auth;
// use App\UserDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\QuestionMaster;
use App\Login;
use App\DatabaseCatalogue;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
// use DB;

class QAnalysisController extends Controller{

 	public function createFeatures(Request $data){
 		if(Auth::user()->role != 2){//only allowed for admin
 			return redirect()->route('login')->with('error', 'Unauthorised Access');
 		}
 		$inactiveUsers = Login::where('active',0)->count();
 		if($data->all()>0)
 		{
 			$rules = [
			            'database' => 'required'
			        ];
			$messages = [   
			            'database.required' =>  'Database name is required',
			        ];
			$validator = Validator::make($data->all(), $rules, $messages);
			if ($validator->fails()) {
	            return view('questions.taskViewer')->withErrors($validator)->with('userDetails', Auth::user())->with('inactiveUsers', $inactiveUsers);
	        }
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
				from `".$data->database."`.test_start_details, 
				(
				select avg(marks_scored) as avgMarks, std(marks_scored) as stdMarks
				from `".$data->database."`.test_start_details
				) as T;");

			//--Calculate weighted Feature 1: Fraction of People who solved the given question correctly
			DB::statement("create table WeightedFeature1 (question_id INT(11) PRIMARY KEY, feature1 FLOAT(7, 5));");
			DB::statement("insert into WeightedFeature1
				select B.question_id, sum(w) / Total as feature1
				from `".$data->database."`.test_ques_ans_dtls B join 
					(
						select question_id, sum(w) as Total
				        from `".$data->database."`.test_ques_ans_dtls T1 join w_values T2 on (T1.test_id = T2.test_id)
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
				from `".$data->database."`.test_start_details A join `".$data->database."`.test_ques_ans_dtls B on(A.id = B.test_id) join w_values C on(B.test_id = C.test_id)
				where (marks = -1 or marks = 0)
				group by question_id
				order by question_id;");

			//-- Calculate Weighted Features
			DB::statement("create table WeightedFeatures (question_id INT(11) PRIMARY KEY, feature1 FLOAT(7, 5), feature2 FLOAT(7, 5), difficulty_level INT(1));");
			DB::statement("insert into WeightedFeatures
				select *
				from WeightedFeature1 natural join WeightedFeature2 natural join (select id as question_id, difficulty_level from `".$data->database."`.question_master) as T
				order by question_id;");

			//-- Remove Redundant Tables
			DB::statement("drop table if exists WeightedFeature1;");
	 		DB::statement("drop table if exists WeightedFeature2;");
	 		DB::statement("drop table if exists w_values;");

 		}
 		


 		// return $fetch;
 		return view('members.home')->with('userDetails', Auth::user());
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
}
