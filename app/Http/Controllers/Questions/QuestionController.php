<?php

namespace App\Http\Controllers\Questions;
use Auth;
// use App\UserDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\QuestionMaster;
use App\Login;
use App\DatabaseCatalogue;
use Illuminate\Support\Facades\Validator;
use DB;

class QuestionController extends Controller{

 	public function quesViewer(Request $data){

 		//fetching database catalogues
 		$years = DatabaseCatalogue::get()->pluck('year');
 		$inactiveUsers = Login::where('active',0)->count();
 		if(sizeof($years)>0)
 		{
	 		if(sizeof($data->all())>0)
	 		{	
	 			// return $data->all() ;
	 			if(!isset($data->new)){//just return the question from id equals qid
	 				$rules = [
			            'qid' => 'integer|min:1'
			        ];
			        $messages = [   
			            'qid.integer' =>  'Question Id must be integer',
			            'qid.min' =>  'Question id must be above 0'
			        ];
			        $validator = Validator::make($data->all(), $rules, $messages);
			        // return (string)$validator->fails();
			        if ($validator->fails()) {
			            return redirect()->route('quesViewer')->withErrors($validator)->with('inactiveUsers', $inactiveUsers);
			        }
			        $fetchQues = QuestionMaster::where('id', $data -> qid)->first();
			        // return $fetchQues;
					if(sizeof($fetchQues)==0)
						return redirect()->route('quesViewer')->with('error',"IPlease enter an ID that falls within the specified range")->with('inactiveUsers', $inactiveUsers);
					else
					{
						$count = QuestionMaster::where('year',$data -> year)->count();
						if($fetchQues->quid == '1')
	 						$previous = 0;
			 			else
			 				$previous = $fetchQues->quid - 1;
			 			if($fetchQues->quid == $count)
			 				$next = 0;
			 			else
			 				$next = $fetchQues->quid + 1;
						return view('questions.viewQuest')->with('userDetails', fAuth::user())->with('defaultyear', $fetchQues->year)->with('category', $fetchQues->category_id)->with('difficulty', $fetchQues->pre_tag)->with('fetchQues',$fetchQues)->with('years',$years)->with("count",$count)->with('previous',$previous)->with('next',$next)->with('inactiveUsers', $inactiveUsers);
					}

	 			}
		        $rules = [
		            'category' => 'integer|min:1|digits_between:1,4',
		            'difficulty' => 'integer|min:0|digits_between:0,3',
		            'year' => 'integer|min:2014|max:2025',
		            'new' => 'in:next,previous,filter,goto',
		            'current' => 'integer|min:1|max:3000|digits_between:1,3000'
		        ];
		        $messages = [   
		            'category.integer' =>  'Category must be integer',
		            'difficulty.integer' =>  'Difficulty level must be integer',
		            'year.integer'        =>  'Year must be integer',
		            'new' =>  'Must be either previous, next, goto or filter',
		            'current.integer' =>  'Question id must be integer',
		            'current.max' =>  'Question id must be between the range given',
		            'current.min' =>  'Question id must be above 0',
		            'current.digits_between' =>  'Question id must be between the range given'

		        ];
		        $validator = Validator::make($data->all(), $rules, $messages);
		        // return (string)$validator->fails();
		        if ($validator->fails()) {
		            return redirect()->route('quesViewer')->withErrors($validator)->with('inactiveUsers', $inactiveUsers);
		        }
	 			$count = QuestionMaster::where('year',$data -> year)->count();
	 			// return $data->all();
	 			$category = $data -> category;
	 			$difficulty = $data -> difficulty;
	 			if($data -> category == '4')//include all categories
	 			{
	 				$data -> category = ['1','2','3'];
	 			}
	 			else{
	 				$data -> category = [$data -> category];
	 			}
	 			if($data -> difficulty == '3')//include all categories
	 			{
	 				$data -> difficulty = ['0','1','2'];
	 			}
	 			else
	 			{
	 				$data -> difficulty = [$data -> difficulty];
	 			}
	 			if($data->new=="next")
					$fetchQues = QuestionMaster::where('year', $data -> year)->where('quid', '>', $data -> current)->whereIn('category_id',$data -> category)->whereIn('pre_tag',$data -> difficulty)->first();
				elseif ($data->new=="filter") {
					// return $data -> category;
					$fetchQues = QuestionMaster::where('year', $data -> year)->whereIn('category_id',$data -> category)->whereIn('pre_tag',$data -> difficulty)->first();
					if(sizeof($fetchQues)==0)
						return view('questions.viewQuest')->with('userDetails', Auth::user())->with('defaultyear', null)->with('category', null)->with('difficulty', null)->with('fetchQues',null)->with('inactiveUsers', $inactiveUsers);
				}
				elseif ($data->new=="goto") {
					$fetchQues = QuestionMaster::where('year', $data -> year)->where('quid',$data -> current)->first();
				}
				else
					$fetchQues = QuestionMaster::where('year', $data -> year)->where('quid', '<', $data -> current)->whereIn('category_id',$data -> category)->whereIn('pre_tag',$data -> difficulty)->orderBy('quid', 'desc')->first();
				if(sizeof($fetchQues)>0)
				{
					if($fetchQues->quid == '1')
	 					$previous = 0;
		 			else
		 				$previous = $fetchQues->quid - 1;
		 			if($fetchQues->quid == $count)
		 				$next = 0;
		 			else
		 				$next = $fetchQues->quid + 1;
					return view('questions.viewQuest')->with('userDetails', Auth::user())->with('years', $years)->with('defaultyear', $data -> year)->with('category', $category)->with('difficulty', $difficulty)->with('fetchQues',$fetchQues)->with('previous',$previous)->with('next',$next)->with("count",$count)->with('inactiveUsers', $inactiveUsers);
				}
				else//if no results than switch to default
				{
					return redirect()->route('quesViewer')->with('error',"Please enter an ID that falls within the specified range")->with('inactiveUsers', $inactiveUsers);
					// $fetchQues = QuestionMaster::where('year', $data -> year)->where('quid', '=', $data -> current)->whereIn('category_id',$data -> category)->whereIn('pre_tag',$data -> difficulty)->first();
					// if($fetchQues->quid == '1')
	 			// 		$previous = 0;
		 		// 	else
		 		// 		$previous = $fetchQues->quid - 1;
		 		// 	if($fetchQues->quid == $count)
		 		// 		$next = 0;
		 		// 	else
		 		// 		$next = $fetchQues->quid + 1;
					// return view('questions.viewQuest')->with('userDetails', Auth::user())->with('years', $years)->with('defaultyear', $data -> year)->with('category', $category)->with('difficulty', $difficulty)->with('fetchQues',$fetchQues)->with('previous',$previous)->with('next',$next)->with("count",$count);
				}
	 		}
	 		// if no request then do this
	 		$count = QuestionMaster::where('year',$years[sizeof($years)-1])->count();
 			$fetchQues = QuestionMaster::where('year',$years[sizeof($years)-1])->first();
 			if(sizeof($fetchQues)== 0)//if no question present for that year
			{
				$fetchQues = QuestionMaster::where('year',$years[sizeof($years)-2])->first();
				$count = QuestionMaster::where('year',$years[sizeof($years)-2])->count();
			}
 			// return $fetchQues;
 			if($fetchQues->quid == '1')
 				$previous = 0;
 			else
 				$previous = $fetchQues->quid - 1;
 			if($fetchQues->quid == $count)
 				$next = 0;
 			else
 				$next = $fetchQues->quid + 1;
 			return view('questions.viewQuest')->with('userDetails', Auth::user())->with('years', $years)->with('defaultyear', $fetchQues->year)->with('category', 4)->with('difficulty', 3)->with('fetchQues',$fetchQues)->with('previous',$previous)->with('next',$next)->with("count",$count)->with('inactiveUsers', $inactiveUsers);
 		}
 			
 		else
 			return view('questions.viewQuest')->with('userDetails', Auth::user())->with('defaultyear', null)->with('category', null)->with('difficulty', null)->with('fetchQues',null)->with('inactiveUsers', $inactiveUsers);
 	}

 	/*****************For editing the question or creating new ones********************/
 	public function quesEditor(Request $data){
 		$inactiveUsers = Login::where('active',0)->count();
 		if(sizeof($data->all())>0)
	 		{		 			
		        if($data->type=='editques'){
		        	$fetchQues = QuestionMaster::where('id', $data -> qid)->first();
		        	// return $fetchQues;
		        	return view('questions.addQuest')->with('qid',$fetchQues->id)->with('fetchQues',$fetchQues)->with('inactiveUsers', $inactiveUsers);

		        }
		        if($data->type=='updateques'){
		        	$rules = [
			 				'qtext' => 'max:1024',
			 				'option1' => 'required|max:255',
			 				'option2' => 'required|max:255',
			 				'option3' => 'required|max:255',
			 				'option4' => 'max:255',
			 				'option5' => 'max:255',
			 				'answeroption' => 'required|min:1|max:5',
				            'category' => 'required|integer|min:1|digits_between:1,4',
				            'difficulty' => 'required|integer|min:0|digits_between:0,3',
				            'year' => 'required|integer|min:2014|max:2025',
				            'type' => 'required|in:newques,editques,updateques'
				        ];
			        $messages = [   
				        	'qtext.max' =>  'Question Text should not be larger than 1024 characters',
				        	'option1.max' =>  'Option 1 should not be larger than 255 characters',
				        	'option2.max' =>  'Option 2 should not be larger than 255 characters',
				        	'option3.max' =>  'Option 3 should not be larger than 255 characters',
				        	'option4.max' =>  'Option 4 should not be larger than 255 characters',
				        	'option5.max' =>  'Option 5 should not be larger than 255 characters',
				        	'answeroption.max' =>  'Answer option should not be larger than 5',
				        	'answeroption.min' =>  'Answer option should not be less than 1',
				            'category.integer' =>  'Category must be integer',
				            'difficulty.integer' =>  'Difficulty level must be integer',
				            'year.integer'        =>  'Year must be integer',
				            'type' =>  'Must be either newques or updateques or editques'
				        ];
			        $validator = Validator::make($data->all(), $rules, $messages);
			        // return (string)$validator->fails();
			        if ($validator->fails()) {
			        	if($data['option4']==''){//checking if the options are valid and agreeing with the answer option
				        	if($data['option5']==''){
				        		if($data['answeroption']>3){
				        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withErrors($validator)->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers);
				        		}
				        	}
				        	else{
				        		if($data['answeroption']>3){
				        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withErrors($validator)->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers);
				        		}
				        		return redirect()->route('quesEditor')->with("error", "Option 5 cannot be filled before option 4")->withErrors($validator)->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers);
				        	}

				        }
				        else{
				        	if($data['answeroption']==5 && $data['option5']==''){
			        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withErrors($validator)->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers);
			        		}
				        }
			            return redirect()->route('quesEditor')->withErrors($validator)->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers);
			        }
		        	// storing the new question
			        if($data['option4']==''){//checking if the options are valid and agreeing with the answer option
			        	if($data['option5']==''){
			        		if($data['answeroption']>3){
			        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers);
			        		}
			        	}
			        	else{
			        		if($data['answeroption']>3){
			        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers);
			        		}
			        		return redirect()->route('quesEditor')->with("error", "Option 5 cannot be filled before option 4")->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers);
			        	}

			        }
			        else{
			        	if($data['answeroption']==5 && $data['option5']==''){
		        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers);
		        		}
			        }
			        DB::transaction(function($data) use ($data){
			        	$fetchQues = QuestionMaster::where('id', $data -> qid)->first();
			            if($data['qtext']!='')
			            	$fetchQues -> question_text =  $data['qtext'];
			            $fetchQues -> option1 =  $data['option1'];
			            $fetchQues -> option2 =  $data['option2'];
			            $fetchQues -> option3 =  $data['option3'];
			            if($data['option4']!='')
			            	$fetchQues -> option4 =  $data['option4'];
			            if($data['option5']!='')
			            	$fetchQues -> option5 =  $data['option5'];
			            $fetchQues -> answer_option1 =  $data['answeroption'];
			            $fetchQues -> pre_tag =  $data['difficulty'];
			            $fetchQues -> category_id =  $data['category'];
			            // $fetchQues -> is_practice_question =  0;
			            $fetchQues -> user_id =  Auth::user()->user_id;
			            $fetchQues ->save();

			        });//end of transaction
			        return redirect()->route('quesEditor')->with("updatesuccess", $data -> qid)->with('qid',0)->with('inactiveUsers', $inactiveUsers);


		        }
		        elseif($data->type=='newques'){
		        	$rules = [
		 				'qtext' => 'max:1024',
		 				'option1' => 'required|max:255',
		 				'option2' => 'required|max:255',
		 				'option3' => 'required|max:255',
		 				'option4' => 'max:255',
		 				'option5' => 'max:255',
		 				'answeroption' => 'required|min:1|max:5',
			            'category' => 'required|integer|min:1|digits_between:1,4',
			            'difficulty' => 'required|integer|min:0|digits_between:0,3',
			            'year' => 'required|integer|min:2014|max:2025',
			            'type' => 'required|in:newques,editques,updateques'
			        ];
		        $messages = [   
			        	'qtext.max' =>  'Question Text should not be larger than 1024 characters',
			        	'option1.max' =>  'Option 1 should not be larger than 255 characters',
			        	'option2.max' =>  'Option 2 should not be larger than 255 characters',
			        	'option3.max' =>  'Option 3 should not be larger than 255 characters',
			        	'option4.max' =>  'Option 4 should not be larger than 255 characters',
			        	'option5.max' =>  'Option 5 should not be larger than 255 characters',
			        	'answeroption.max' =>  'Answer option should not be larger than 5',
			        	'answeroption.min' =>  'Answer option should not be less than 1',
			            'category.integer' =>  'Category must be integer',
			            'difficulty.integer' =>  'Difficulty level must be integer',
			            'year.integer'        =>  'Year must be integer',
			            'type' =>  'Must be either newques or updateques or editques'
			        ];
		        $validator = Validator::make($data->all(), $rules, $messages);
		        // return (string)$validator->fails();
		        if ($validator->fails()) {
		        	if($data['option4']==''){//checking if the options are valid and agreeing with the answer option
			        	if($data['option5']==''){
			        		if($data['answeroption']>3){
			        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withErrors($validator)->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers);
			        		}
			        	}
			        	else{
			        		if($data['answeroption']>3){
			        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withErrors($validator)->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers);
			        		}
			        		return redirect()->route('quesEditor')->with("error", "Option 5 cannot be filled before option 4")->withErrors($validator)->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers);
			        	}

			        }
			        else{
			        	if($data['answeroption']==5 && $data['option5']==''){
		        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withErrors($validator)->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers);
		        		}
			        }
		            return redirect()->route('quesEditor')->withErrors($validator)->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers);
		        }
	        	// storing the new question
		        if($data['option4']==''){//checking if the options are valid and agreeing with the answer option
		        	if($data['option5']==''){
		        		if($data['answeroption']>3){
		        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers);
		        		}
		        	}
		        	else{
		        		if($data['answeroption']>3){
		        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers);
		        		}
		        		return redirect()->route('quesEditor')->with("error", "Option 5 cannot be filled before option 4")->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers);
		        	}

		        }
		        else{
		        	if($data['answeroption']==5 && $data['option5']==''){
	        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers);
	        		}
		        }
		        DB::transaction(function($data) use ($data){
		        	$fetchlastId = QuestionMaster::where('year', $data -> year)->orderBy('quid', 'desc')->first();

		            $newQues = new QuestionMaster;
		            if(sizeof($fetchlastId)>0)
		            	$newQues -> quid =  $fetchlastId->quid+1;
		            else
		            	$newQues -> quid =  1;
		            $newQues -> year =  $data['year'];
		            if($data['qtext']!='')
		            	$newQues -> question_text =  $data['qtext'];            
		            $newQues -> question_type =  0;
		            $newQues -> option1 =  $data['option1'];
		            $newQues -> option2 =  $data['option2'];
		            $newQues -> option3 =  $data['option3'];
		            if($data['option4']!='')
		            	$newQues -> option4 =  $data['option4'];
		            if($data['option5']!='')
		            	$newQues -> option5 =  $data['option5'];
		            $newQues -> answer_option1 =  $data['answeroption'];
		            $newQues -> pre_tag =  $data['difficulty'];
		            $newQues -> category_id =  $data['category'];
		            // $newQues -> is_practice_question =  0;
		            $newQues -> user_id =  Auth::user()->user_id;
		            $newQues ->save();
		            $data->qid = $newQues->id;

		        });//end of transaction
		        return redirect()->route('quesEditor')->with("createsuccess", $data->qid)->with('qid',0)->with('inactiveUsers', $inactiveUsers);


	        }
	        
 		}
 		return view('questions.addQuest')->with('qid',0)->with('inactiveUsers', $inactiveUsers);
 	}
 	public function showStats(){
 		$inactiveUsers = Login::where('active',0)->count();
 		return view('questions.showStat')->with('userDetails', Auth::user())->with('inactiveUsers', $inactiveUsers);
 	}
 	public function showTasks(){
 		$inactiveUsers = Login::where('active',0)->count();
 		return view('questions.taskViewer')->with('userDetails', Auth::user())->with('inactiveUsers', $inactiveUsers);
 	}
}
