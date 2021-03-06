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
use File;
use App\TestSetMaster;
use App\TestSetQuestionMap;
use App\ExpQuesMap;
use Session;

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
	 			if(!isset($data->new)){//just return the question from id equals qid, new here signifies next or prev qustion, which in case is not present
	 				$rules = [
			            'qid' => 'integer|min:1'
			        ];
			        $messages = [   
			            'qid.integer' =>  'Question Id must be integer',
			            'qid.min' =>  'Question Id must be above 0'
			        ];
			        $validator = Validator::make($data->all(), $rules, $messages);
			        // return (string)$validator->fails();
			        if ($validator->fails()) {
			            return redirect()->route('quesViewer')->withErrors($validator)->with('inactiveUsers', $inactiveUsers);
			        }
			        $fetchQues = QuestionMaster::where('id', $data -> qid)->first();
			        // $fetchQuesHist = QuestionMaster::where('quid', $fetchQues -> quid)->get();
			        $fetchQuesHist = QuestionMaster::with('user')->where('year', $fetchQues -> year)->where('quid', $fetchQues -> quid)->get();
			        // return $fetchQues;
					if(sizeof($fetchQues)==0)
						return redirect()->route('quesViewer')->with('error',"Please enter an ID that falls within the specified range")->with('inactiveUsers', $inactiveUsers);
					else
					{
						// $count = QuestionMaster::where('year',$data -> year)->count();
						$count = QuestionMaster::where('year',$fetchQues -> year)->where('active', '=', 1)->count(); //added active question only
						if($fetchQues->quid == '1')
	 						$previous = 0;
			 			else
			 				$previous = $fetchQues->quid - 1;
			 			if($fetchQues->quid == $count)
			 				$next = 0;
			 			else
			 				$next = $fetchQues->quid + 1;
			 			// return $count;
						return view('questions.viewQuest')->with('userDetails', Auth::user())->with('defaultyear', $fetchQues->year)->with('category', $fetchQues->category_id)->with('difficulty', $fetchQues->pre_tag)->with('fetchQues',$fetchQues)->with('years',$years)->with("count",$count)->with('previous',$previous)->with('next',$next)->with('inactiveUsers', $inactiveUsers)->with('fetchQuesHist', $fetchQuesHist);
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
	 			// $count = QuestionMaster::where('year',$data -> year)->count();
	 			$count = QuestionMaster::where('year',$data -> year)->where('active', '=', 1)->count(); //added active question only
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
				{
					// return $data -> difficulty;
					$fetchQues = QuestionMaster::where('year', $data -> year)->where('quid', '>', $data -> current)->whereIn('category_id',$data -> category)->whereIn('pre_tag',$data -> difficulty)->where('active', '=', 1)->orderBy('quid', 'asc')->first();
					// return $fetchQues;
				}
				elseif ($data->new=="filter") {
					// return $data -> category;
					$fetchQues = QuestionMaster::where('year', $data -> year)->whereIn('category_id',$data -> category)->whereIn('pre_tag',$data -> difficulty)->where('active', '=', 1)->first();
					if(sizeof($fetchQues)==0)
					{
						return view('questions.viewQuest')->with('userDetails', Auth::user())->with('defaultyear', null)->with('category', null)->with('difficulty', null)->with('fetchQues',null)->with('inactiveUsers', $inactiveUsers)->with('fetchQuesHist', null);
					}
				}
				elseif ($data->new=="goto") {
					$fetchQues = QuestionMaster::where('year', $data -> year)->where('quid',$data -> current)->where('active', '=', 1)->first();
				}
				else
					$fetchQues = QuestionMaster::where('year', $data -> year)->where('quid', '<', $data -> current)->whereIn('category_id',$data -> category)->whereIn('pre_tag',$data -> difficulty)->where('active', '=', 1)->orderBy('quid', 'desc')->first();
				// return $fetchQues;
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
		 			$fetchQuesHist = QuestionMaster::with('user')->where('year', $fetchQues -> year)->where('quid', $fetchQues -> quid)->get();
					return view('questions.viewQuest')->with('userDetails', Auth::user())->with('years', $years)->with('defaultyear', $data -> year)->with('category', $category)->with('difficulty', $difficulty)->with('fetchQues',$fetchQues)->with('previous',$previous)->with('next',$next)->with("count",$count)->with('inactiveUsers', $inactiveUsers)->with('fetchQuesHist', $fetchQuesHist);
				}

				else//if no results than switch to defaultreturn $fetchQuesHist;
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
	 		// $count = QuestionMaster::where('year',$years[sizeof($years)-1])->count();
	 		$count = QuestionMaster::where('year',$years[sizeof($years)-1])->where('active', '=', 1)->count(); //added active question only
 			$fetchQues = QuestionMaster::where('year',$years[sizeof($years)-1])->where('active', '=', 1)->first();
 			if(sizeof($fetchQues)== 0)//if no question present for that year
			{
				$fetchQues = QuestionMaster::where('year',$years[sizeof($years)-2])->where('active', '=', 1)->first();
				// $count = QuestionMaster::where('year',$years[sizeof($years)-2])->count();
	 			$count = QuestionMaster::where('year',$years[sizeof($years)-2])->where('active', '=', 1)->count(); //added active question only

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
 			$fetchQuesHist = QuestionMaster::with('user')->where('year', $fetchQues -> year)->where('quid', $fetchQues -> quid)->get();
 			return view('questions.viewQuest')->with('userDetails', Auth::user())->with('years', $years)->with('defaultyear', $fetchQues->year)->with('category', 4)->with('difficulty', 3)->with('fetchQues',$fetchQues)->with('previous',$previous)->with('next',$next)->with("count",$count)->with('inactiveUsers', $inactiveUsers)->with('fetchQuesHist', $fetchQuesHist);
 		}
 			
 		else{
 			// $fetchQuesHist = QuestionMaster::where('year', $data -> year)->where('quid', $fetchQues -> quid)->get();
 			return view('questions.viewQuest')->with('userDetails', Auth::user())->with('defaultyear', null)->with('category', null)->with('difficulty', null)->with('fetchQues',null)->with('inactiveUsers', $inactiveUsers)->with('fetchQuesHist', null);
 		}
 	}

 	/*****************For editing the question or creating new ones********************/
 	public function quesEditor(Request $data){
 		$inactiveUsers = Login::where('active',0)->count();//for the red notification add the admin bar
 		$years = DatabaseCatalogue::get()->pluck('year');
 		if(sizeof($data->all())>0)
	 		{		 			
		        if($data->type=='editques'){//prefillup the form
		        	$fetchQues = QuestionMaster::where('id', $data -> qid)->first();
		        	// return $fetchQues;
		        	$fetchQuesHist = QuestionMaster::with('user')->where('year', $fetchQues -> year)->where('quid', $fetchQues -> quid)->get();
		        	return view('questions.addQuest')->with('qid',$fetchQues->id)->with('fetchQues',$fetchQues)->with('inactiveUsers', $inactiveUsers)->with('years', $years)->with('fetchQuesHist',$fetchQuesHist);

		        }
		        if($data->type=='updateques'){ //update question with new info, creates revision
		        	$rules = [
			 				'qtext' => 'max:1024',
			 				'option1' => 'required|max:255',
			 				'option2' => 'required|max:255',
			 				'option3' => 'required|max:255',
			 				'option4' => 'max:255',
			 				'option5' => 'max:255',
			 				'answeroption' => 'required|min:1|max:5',
		 					'questionimage' => 'image|mimes:jpeg,bmp,png|max:2048',//max size 2MB
				            'category' => 'required|integer|min:1|digits_between:1,4',
				            'difficulty' => 'required|integer|min:0|digits_between:0,3',
				            'year' => 'required|integer|min:2014|max:2025',
				            'type' => 'required|in:newques,editques,updateques',
				            'addimage' => 'integer|max:1',
				            'active' => 'in:1',
				            'editfield' => 'integer|max:1'
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
				            'type' =>  'Must be either newques or updateques or editques',
				            'addimage' => 'Must be valid integer',
				            'active' => 'Must valid integer',
				            'editfield' => 'Must valid integer'
				        ];
			        $validator = Validator::make($data->all(), $rules, $messages);
			        // return (string)$validator->fails();
			        if ($validator->fails()) {
			        	if($data['option4']==''){//checking if the options are valid and agreeing with the answer option
				        	if($data['option5']==''){
				        		if($data['answeroption']>3){
				        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withErrors($validator)->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
				        		}
				        	}
				        	else{
				        		if($data['answeroption']>3){
				        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withErrors($validator)->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
				        		}
				        		return redirect()->route('quesEditor')->with("error", "Option 5 cannot be filled before option 4")->withErrors($validator)->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
				        	}

				        }
				        else{
				        	if($data['answeroption']==5 && $data['option5']==''){
			        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withErrors($validator)->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
			        		}
				        }
			            return redirect()->route('quesEditor')->withErrors($validator)->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
			        }
		        	
			        if($data['option4']==''){//checking if the options are valid and agreeing with the answer option
			        	if($data['option5']==''){
			        		if($data['answeroption']>3){
			        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
			        		}
			        	}
			        	else{
			        		if($data['answeroption']>3){
			        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
			        		}
			        		return redirect()->route('quesEditor')->with("error", "Option 5 cannot be filled before option 4")->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
			        	}

			        }
			        else{
			        	if($data['answeroption']==5 && $data['option5']==''){
		        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
		        		}
			        }
			        if($data['editfield']=='1'){//edit the question description and create new revision history
			        	//if($data['active']=='1'){
			        		DB::transaction(function($data) use ($data){
			       
			        		$fetchQues = QuestionMaster::where('id', $data -> qid)->first();
	 						$count = QuestionMaster::where('quid', $data['quid'])->where('year', $fetchQues->year)->count(); //find revision count

				            $newQues = new QuestionMaster;
				           	$newQues -> quid =  $data['quid']; //copy old question as new revision
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
				            
				            if($data['addimage']=='1') // remove image entry, dont delete the actual image
				            {
				            	$newQues -> question_img =  Null;
				            }
				            else
				            	$newQues -> question_img = $fetchQues->question_img; //copy command should be here

				            if ($data->hasFile('questionimage') and $data['addimage']!='1') {
				            	$filename = $this->getFileName($data->questionimage);
	 							$data->questionimage->move(base_path('public/img/qwdara/'.$data -> year), $filename);
				            	$newQues -> question_img =  $filename;
				            }

				            $newQues -> answer_option1 =  $data['answeroption'];
				            // $newQues -> pre_tag =  $data['difficulty'];
				            $newQues -> difficulty_level =  $data['difficulty'];//current tag
				            $newQues -> pre_tag =  $data['difficulty']; // //pre tag similar to current, since this question is not analyzed
				            $newQues -> category_id =  $data['category'];
				            $newQues -> user_id =  Auth::user()->user_id;
				            if($data['active']=='1')
				            {
				            	$newQues -> active =  $data['active']; // this revised question is active, else default is 0 for inactive
				            	//mark other questions with same id inactive
				            	QuestionMaster::with('user')->where('year', $fetchQues -> year)->where('quid', $fetchQues -> quid)->update(['active'=> 0]);
				            }
				            else
				            	$newQues -> active =  0; // this revised question is active, else 0 for inactive
				            $newQues -> revision_count =  $count; // revision count
				            $newQues ->save();
				            $data->qid = $newQues->id;

				        });//end of transaction
			        		return redirect()->route('quesEditor')->with("revisesuccess", $data->qid)->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);

			        	//}

			        }
			        else//just mark the question as active
			        { // just mark it as active or inactive
				        DB::transaction(function($data) use ($data){
				       
				        	$fetchQues = QuestionMaster::where('id', $data -> qid)->first();
				        	if($data['active']=='1')
				        	{
				            	$fetchQues -> active =  $data['active']; // this revised question is active, else default is 0 for inactive
				            	//mark other questions with same id inactive
				            	QuestionMaster::with('user')->where('year', $fetchQues -> year)->where('quid', $fetchQues -> quid)->update(['active'=> 0]);
				        	}
				            $fetchQues ->save();
				     //        if($data['qtext']!='')
				     //        	$fetchQues -> question_text =  $data['qtext'];
				     //        $fetchQues -> option1 =  $data['option1'];
				     //        $fetchQues -> option2 =  $data['option2'];
				     //        $fetchQues -> option3 =  $data['option3'];
				     //        if($data['option4']!='')
				     //        	$fetchQues -> option4 =  $data['option4'];
				     //        if($data['option5']!='')
				     //        	$fetchQues -> option5 =  $data['option5'];
				     //        if ($data->hasFile('questionimage') and $data['addimage']!='1') {
				     //        	$filename = $this->getFileName($data->questionimage);
	 							// $data->questionimage->move(base_path('public/img/qwdara/'.$fetchQues -> year), $filename);
				     //        	$fetchQues -> question_img =  $filename;
				     //        }
				     //        if($data['addimage']=='1') // remove image
				     //        {
				     //        	$image_path = $fetchQues -> question_img;
				     //        	$fetchQues -> question_img =  Null;
				     //        }
				     //        $fetchQues -> answer_option1 =  $data['answeroption'];
				     //        $fetchQues -> year =  $data['year'];
				     //        $fetchQues -> pre_tag =  $data['difficulty'];
				     //        $fetchQues -> difficulty_level =  $data['difficulty'];
				     //        $fetchQues -> category_id =  $data['category'];
				     //        // $fetchQues -> is_practice_question =  0;
				     //        $fetchQues -> user_id =  Auth::user()->user_id;
				     //        $fetchQues ->save();

				            // for deleting the image
				         //    if($data['addimage']=='1') // remove image file, i put this in last, coz i wanted the query to be executted successfully
				         //    {
					        //     $image_path = public_path().'/img/qwdara/'.$fetchQues -> year.'/'.$image_path; 
					        //     // unlink($image_path);
					        //     File::delete($image_path);
					            
					        // }
					        
				        });//end of transaction
				        return redirect()->route('quesEditor')->with("updatesuccess", $data -> qid)->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
				    }


		        }//end of update question
		        elseif($data->type=='newques'){
		        	$rules = [
		 				'qtext' => 'max:1024',
		 				'option1' => 'required|max:255',
		 				'option2' => 'required|max:255',
		 				'option3' => 'required|max:255',
		 				'option4' => 'max:255',
		 				'option5' => 'max:255',
		 				'questionimage' => 'image|mimes:jpeg,bmp,png|max:2048',//max size 2MB
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
			        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withErrors($validator)->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
			        		}
			        	}
			        	else{
			        		if($data['answeroption']>3){
			        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withErrors($validator)->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
			        		}
			        		return redirect()->route('quesEditor')->with("error", "Option 5 cannot be filled before option 4")->withErrors($validator)->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
			        	}

			        }
			        else{
			        	if($data['answeroption']==5 && $data['option5']==''){
		        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withErrors($validator)->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
		        		}
			        }
		            return redirect()->route('quesEditor')->withErrors($validator)->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
		        }
	        	// storing the new question
		        if($data['option4']==''){//checking if the options are valid and agreeing with the answer option
		        	if($data['option5']==''){
		        		if($data['answeroption']>3){
		        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
		        		}
		        	}
		        	else{
		        		if($data['answeroption']>3){
		        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
		        		}
		        		return redirect()->route('quesEditor')->with("error", "Option 5 cannot be filled before option 4")->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
		        	}

		        }
		        else{
		        	if($data['answeroption']==5 && $data['option5']==''){
	        			return redirect()->route('quesEditor')->with("error", "Please choose valid answer option")->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
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
		            if ($data->hasFile('questionimage')) {
		            	$filename = $this->getFileName($data->questionimage);
							$data->questionimage->move(base_path('public/img/qwdara/'.$data -> year), $filename);
		            	$newQues -> question_img =  $filename;
		            }
		            $newQues -> answer_option1 =  $data['answeroption'];
		            // $newQues -> pre_tag =  $data['difficulty'];
		            $newQues -> difficulty_level =  $data['difficulty'];
		            $newQues -> pre_tag =  $data['difficulty'];
		            $newQues -> category_id =  $data['category'];
		            // $newQues -> is_practice_question =  0;
		            $newQues -> user_id =  Auth::user()->user_id;
		            $newQues ->save();
		            $data->qid = $newQues->id;

		        });//end of transaction
		        return redirect()->route('quesEditor')->with("createsuccess", $data->qid)->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);


	        }
	        
 		}
 		return view('questions.addQuest')->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
 	}

 	/*****************For creating new Experimental questions********************/
 	public function expQuest(Request $data){
 		$inactiveUsers = Login::where('active',0)->count();//for the red notification add the admin bar
 		$years = DatabaseCatalogue::get()->pluck('year');
 		if(sizeof($data->all())>0)
	 		{		 			
		        if($data->type=='expQuest'){//prefillup the form
		        	$fetchQues = QuestionMaster::where('id', $data -> qid)->first();
		        	// return $fetchQues;
		        	return view('questions.expQuest')->with('qid',$fetchQues->id)->with('fetchQues',$fetchQues)->with('inactiveUsers', $inactiveUsers)->with('years', $years);

		        }
		        if($data->type=='createQues'){ //update question with new info, creates revision
		        	
		        	$rules = [
			 				'qtext1' => 'max:1024',
			 				'option11' => 'required|max:255',
			 				'option12' => 'required|max:255',
			 				'option13' => 'required|max:255',
			 				'option14' => 'max:255',
			 				'option15' => 'max:255',
			 				'answeroption1' => 'required|min:1|max:5',
		 					'questionimage1' => 'image|mimes:jpeg,bmp,png|max:2048',//max size 2MB
				            'category1' => 'required|integer|min:1|digits_between:1,4',
				            'difficulty1' => 'required|integer|min:0|digits_between:0,3',
				            'year1' => 'required|integer|min:2014|max:2025',
				            'type' => 'required|in:createQues',
				            'addimage1' => 'integer|max:1',
				            'qtext2' => 'max:1024',
			 				'option21' => 'required|max:255',
			 				'option22' => 'required|max:255',
			 				'option23' => 'required|max:255',
			 				'option24' => 'max:255',
			 				'option25' => 'max:255',
			 				'answeroption2' => 'required|min:1|max:5',
		 					'questionimage2' => 'image|mimes:jpeg,bmp,png|max:2048',//max size 2MB
				            'category2' => 'required|integer|min:1|digits_between:1,4',
				            'difficulty2' => 'required|integer|min:0|digits_between:0,3',
				            'year2' => 'required|integer|min:2014|max:2025',
				            'addimage2' => 'integer|max:1'
				        ];
			        $messages = [   
				        	'qtext1.max' =>  'Question Text should not be larger than 1024 characters',
				        	'option11.max' =>  'Option 1 should not be larger than 255 characters',
				        	'option12.max' =>  'Option 2 should not be larger than 255 characters',
				        	'option13.max' =>  'Option 3 should not be larger than 255 characters',
				        	'option14.max' =>  'Option 4 should not be larger than 255 characters',
				        	'option15.max' =>  'Option 5 should not be larger than 255 characters',
				        	'answeroption1.max' =>  'Answer option should not be larger than 5',
				        	'answeroption1.min' =>  'Answer option should not be less than 1',
				            'category1.integer' =>  'Category must be integer',
				            'difficulty1.integer' =>  'Difficulty level must be integer',
				            'year1.integer'        =>  'Year must be integer',
				            'addimage1' => 'Must be valid integer',
				            'qtext2.max' =>  'Question Text should not be larger than 1024 characters',
				        	'option21.max' =>  'Option 1 should not be larger than 255 characters',
				        	'option22.max' =>  'Option 2 should not be larger than 255 characters',
				        	'option23.max' =>  'Option 3 should not be larger than 255 characters',
				        	'option24.max' =>  'Option 4 should not be larger than 255 characters',
				        	'option25.max' =>  'Option 5 should not be larger than 255 characters',
				        	'answeroption2.max' =>  'Answer option should not be larger than 5',
				        	'answeroption2.min' =>  'Answer option should not be less than 1',
				            'category2.integer' =>  'Category must be integer',
				            'difficulty2.integer' =>  'Difficulty level must be integer',
				            'year2.integer'        =>  'Year must be integer',
				            'type' =>  'Must be createQues',
				            'addimage2' => 'Must be valid integer'

				        ];
			        $validator = Validator::make($data->all(), $rules, $messages);
			        // return (string)$validator->fails();
			        if ($validator->fails()) {
			        	if($data['option14']==''){//checking if the options are valid and agreeing with the answer option
				        	if($data['option15']==''){
				        		if($data['answeroption1']>3){
				        			return redirect()->route('expQuest')->with("error", "Please choose valid answer option for first Question")->withErrors($validator)->withInput($data->all())->with('qid',$data->qid)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
				        		}
				        	}
				        	else{
				        		if($data['answeroption1']>3){
				        			return redirect()->route('expQuest')->with("error", "Please choose valid answer option for first Question")->withErrors($validator)->withInput($data->all())->with('qid',$data->qid)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
				        		}
				        		return redirect()->route('expQuest')->with("error", "Option 5 cannot be filled before option 4 for first Question")->withErrors($validator)->withInput($data->all())->with('qid',$data->qid)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
				        	}

				        }
				        else{
				        	if($data['answeroption1']==5 && $data['option15']==''){
			        			return redirect()->route('expQuest')->with("error", "Please choose valid answer option for first Question")->withErrors($validator)->withInput($data->all())->with('qid',$data->qid)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
			        		}
				        }

				        // Question 2
				        if($data['option24']==''){//checking if the options are valid and agreeing with the answer option
				        	if($data['option25']==''){
				        		if($data['answeroption2']>3){
				        			return redirect()->route('expQuest')->with("error", "Please choose valid answer option for second Question")->withErrors($validator)->withInput($data->all())->with('qid',$data->qid)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
				        		}
				        	}
				        	else{
				        		if($data['answeroption2']>3){
				        			return redirect()->route('expQuest')->with("error", "Please choose valid answer option for second Question")->withErrors($validator)->withInput($data->all())->with('qid',$data->qid)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
				        		}
				        		return redirect()->route('expQuest')->with("error", "Option 5 cannot be filled before option 4 for second Question")->withErrors($validator)->withInput($data->all())->with('qid',$data->qid)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
				        	}

				        }
				        else{
				        	if($data['answeroption2']==5 && $data['option25']==''){
			        			return redirect()->route('expQuest')->with("error", "Please choose valid answer option for second Question")->withErrors($validator)->withInput($data->all())->with('qid',$data->qid)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
			        		}
				        }
			            return redirect()->route('expQuest')->withErrors($validator)->withInput($data->all())->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
			        }
		        	
			        if($data['option14']==''){//checking if the options are valid and agreeing with the answer option
			        	if($data['option15']==''){
			        		if($data['answeroption1']>3){
			        			return redirect()->route('expQuest')->with("error", "Please choose valid answer option for first Question")->withInput($data->all())->with('qid',$data->qid)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
			        		}
			        	}
			        	else{
			        		if($data['answeroption1']>3){
			        			return redirect()->route('expQuest')->with("error", "Please choose valid answer option for first Question")->withInput($data->all())->with('qid',$data->qid)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
			        		}
			        		return redirect()->route('expQuest')->with("error", "Option 5 cannot be filled before option 4 for first Question")->withInput($data->all())->with('qid',$data->qid)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
			        	}

			        }
			        else{
			        	if($data['answeroption1']==5 && $data['option15']==''){
		        			return redirect()->route('expQuest')->with("error", "Please choose valid answer option for first Question")->withInput($data->all())->with('qid',$data->qid)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
		        		}
			        }
			        //Question 2
			        if($data['option24']==''){//checking if the options are valid and agreeing with the answer option
			        	if($data['option25']==''){
			        		if($data['answeroption2']>3){
			        			return redirect()->route('expQuest')->with("error", "Please choose valid answer option for second Question")->withInput($data->all())->with('qid',$data->qid)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
			        		}
			        	}
			        	else{
			        		if($data['answeroption2']>3){
			        			return redirect()->route('expQuest')->with("error", "Please choose valid answer option for second Question")->withInput($data->all())->with('qid',$data->qid)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
			        		}
			        		return redirect()->route('expQuest')->with("error", "Option 5 cannot be filled before option 4 for second Question")->withInput($data->all())->with('qid',$data->qid)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
			        	}

			        }
			        else{
			        	if($data['answeroption2']==5 && $data['option25']==''){
		        			return redirect()->route('expQuest')->with("error", "Please choose valid answer option for second Question")->withInput($data->all())->with('qid',$data->qid)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
		        		}
			        }
			        	//if($data['active']=='1'){
	        		DB::transaction(function($data) use ($data){
	        			//first question
		        		$fetchlastId = QuestionMaster::where('year', $data -> year1)->orderBy('quid', 'desc')->first();//fetch last id
		        		$fetchQues = QuestionMaster::where('id', $data -> qid)->first();//old data
			            $newQues = new QuestionMaster;
			           	$newQues -> quid =  $fetchlastId['quid']+1; 
			            $newQues -> year =  $data['year1'];
			            if($data['qtext1']!='')
			            	$newQues -> question_text =  $data['qtext1'];            
			            $newQues -> question_type =  0;
			            $newQues -> option1 =  $data['option11'];
			            $newQues -> option2 =  $data['option12'];
			            $newQues -> option3 =  $data['option13'];
			            if($data['option14']!='')
			            	$newQues -> option4 =  $data['option14'];
			            if($data['option15']!='')
			            	$newQues -> option5 =  $data['option15'];
			            
			            if($data['addimage1']=='1') // remove image entry, dont delete the actual image
			            {
			            	$newQues -> question_img =  Null;
			            }
			            else
			            	$newQues -> question_img = $fetchQues->question_img; //copy command should be here

			            if ($data->hasFile('questionimage1') and $data['addimage1']!='1') {
			            	$filename = $this->getFileName($data->questionimage1);
								$data->questionimage1->move(base_path('public/img/qwdara/'.$data -> year1), $filename);
			            	$newQues -> question_img =  $filename;
			            }

			            $newQues -> answer_option1 =  $data['answeroption1'];
			            // $newQues -> pre_tag =  $data['difficulty'];
			            $newQues -> difficulty_level =  $data['difficulty1'];//current tag
			            $newQues -> pre_tag =  $data['difficulty1']; // //pre tag similar to current, since this question is not analyzed
			            $newQues -> category_id =  $data['category1'];
			            $newQues -> user_id =  Auth::user()->user_id;
			           
		            	$newQues -> active =  1; // mark it active
			            $newQues ->save();
			            ExpQuesMap::insert(['ques_id'=> $data->qid, 'mapped_ques_id' => $newQues->id]);

			            // second question
			            $fetchlastId = QuestionMaster::where('year', $data -> year2)->orderBy('quid', 'desc')->first();//fetch last id
		        		$fetchQues = QuestionMaster::where('id', $data -> qid)->first();//old data
			            $newQues = new QuestionMaster;
			           	$newQues -> quid =  $fetchlastId['quid']+1; 
			            $newQues -> year =  $data['year2'];
			            if($data['qtext2']!='')
			            	$newQues -> question_text =  $data['qtext2'];            
			            $newQues -> question_type =  0;
			            $newQues -> option1 =  $data['option21'];
			            $newQues -> option2 =  $data['option22'];
			            $newQues -> option3 =  $data['option23'];
			            if($data['option24']!='')
			            	$newQues -> option4 =  $data['option24'];
			            if($data['option25']!='')
			            	$newQues -> option5 =  $data['option25'];
			            
			            if($data['addimage2']=='1') // remove image entry, dont delete the actual image
			            {
			            	$newQues -> question_img =  Null;
			            }
			            else
			            	$newQues -> question_img = $fetchQues->question_img; //copy command should be here

			            if ($data->hasFile('questionimage2') and $data['addimage2']!='1') {
			            	$filename = $this->getFileName($data->questionimage1);
								$data->questionimage1->move(base_path('public/img/qwdara/'.$data -> year2), $filename);
			            	$newQues -> question_img =  $filename;
			            }

			            $newQues -> answer_option1 =  $data['answeroption2'];
			            // $newQues -> pre_tag =  $data['difficulty'];
			            $newQues -> difficulty_level =  $data['difficulty2'];//current tag
			            $newQues -> pre_tag =  $data['difficulty2']; // //pre tag similar to current, since this question is not analyzed
			            $newQues -> category_id =  $data['category2'];
			            $newQues -> user_id =  Auth::user()->user_id;
			           
		            	$newQues -> active =  1; // mark it active
			            $newQues ->save();

			            ExpQuesMap::insert(['ques_id'=> $data->qid, 'mapped_ques_id' => $newQues->id]);

			        });//end of transaction
				$fetchExpQuestion = ExpQuesMap::where('ques_id', '=', $data->qid)->get();
	        	return redirect()->route('expQuest')->with("createsuccess", $fetchExpQuestion)->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
	


		        }//end of experimental questions
	        
 		}
 		return view('questions.expQuest')->with('qid',0)->with('inactiveUsers', $inactiveUsers)->with('years', $years);
 	}

 	/******************For managing question for Selection Test inclusion***************/
 	public function setManage(Request $data){

 		//fetching database catalogues
 		$years = DatabaseCatalogue::get()->pluck('year');
 		$inactiveUsers = Login::where('active',0)->count();
 		if(sizeof($years)>0)
 		{
	 		if(sizeof($data->all())>0){
	 			// return $data->all();
	 			
	 			$rules = [
		            'category' => 'integer|min:1|digits_between:1,4',
		            'difficulty' => 'integer|min:0|digits_between:0,3',
		            'year' => 'in:All,2014,2015,2016,2017,2018,2019,2020,2021,2022,2023,2024,2025',
		            'new' => 'in:next,previous,filter,goto',
		            'isSelected' => 'in:All,0,1',
		            'resultCount' => 'integer|min:10|max:100'
		        ];
		        $messages = [   
		            'category.integer' =>  'Category must be integer',
		            'difficulty.integer' =>  'Difficulty level must be integer',
		            'year.integer'        =>  'Year must be integer',
		            'new' =>  'Must be either previous, next, goto or filter',
		            'isSelected' => 'isSelected should be All or 0 or 1',
		            'resultCount' => 'Result count should be between 10 to 100'

		        ];
		        $validator = Validator::make($data->all(), $rules, $messages);
		        // return (string)$validator->fails();
		        if ($validator->fails()) {
		        	// return 'fail';
		            return redirect()->route('quesSet')->withErrors($validator)->with('inactiveUsers', $inactiveUsers);
		        }
		        if($data->new=='filter')
		        	Session::put('seed', time()); //seed
		        $category = $data -> category;
	 			$difficulty = $data -> difficulty;
	 			$year = $data -> year;
	 			$isSelected = $data -> isSelected;
	 			if($data -> isSelected == 'All')//include all yes or no
	 			{
	 				$data -> isSelected = [0,1];
	 			}
	 			else{
	 				$data -> isSelected = [$data -> isSelected];
	 			}
	 			if($data -> year == 'All')//include all years
	 			{
	 				$year = $years;
	 			}
	 			else{
	 				$year = [$data -> year];
	 			}
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
	 			$count = QuestionMaster::whereIn('year', $year)->whereIn('for_selectionTest', $data -> isSelected)->whereIn('category_id',$data -> category)->whereIn('difficulty_level',$data -> difficulty)->where('active', '=', 1)->count(); //added active question only
 				// $fetchQues = QuestionMaster::whereIn('year',$years)->where('active', '=', 1)->paginate(10);
 				$fetchQues = QuestionMaster::whereIn('year', $year)->whereIn('for_selectionTest', $data -> isSelected)->whereIn('category_id',$data -> category)->whereIn('difficulty_level',$data -> difficulty)->where('active', '=', 1)->inRandomOrder(Session::get('seed'))->paginate($data -> resultCount);
 				// return $fetchQues;
 				// return $data->all();
 				$summary = $this->getSummary();
				$apti = $summary[0];
				$elec = $summary[1];
				$prog = $summary[2];
				$summary = array('apti' => $apti, 'elec' => $elec, 'prog' => $prog);
 				return view('questions.setCreate')->with('userDetails', Auth::user())->with('years', $years)->with('defaultyear', $data -> year)->with('category', $category)->with('difficulty', $difficulty)->with('isSelected',$isSelected)->with('resultCount',$data -> resultCount)->with('fetchQues',$fetchQues)->with("count",$count)->with('inactiveUsers', $inactiveUsers)->with('summary',$summary);
	 		}
	 		
	 		$count = QuestionMaster::whereIn('year',$years)->where('active', '=', 1)->count(); //added active question only
	 		$fetchQues = QuestionMaster::whereIn('year', $years)->whereIn('for_selectionTest', [0,1])->whereIn('category_id',[1,2,3])->whereIn('difficulty_level',[0,1,2])->where('active', '=', 1)->inRandomOrder(Session::get('seed'))->paginate(10);
	 		$summary = $this->getSummary();
			$apti = $summary[0];
			$elec = $summary[1];
			$prog = $summary[2];
			$summary = array('apti' => $apti, 'elec' => $elec, 'prog' => $prog);
 			return view('questions.setCreate')->with('userDetails', Auth::user())->with('years', $years)->with('defaultyear', 'All')->with('category', 4)->with('difficulty', 3)->with('isSelected','All')->with('resultCount',10)->with('fetchQues',$fetchQues)->with("count",$count)->with('inactiveUsers', $inactiveUsers)->with('summary',$summary);
 		}
 			
 		else{//no database present 
 			return view('questions.setCreate')->with('userDetails', Auth::user())->with('defaultyear', null)->with('category', null)->with('difficulty', null)->with('isSelected','All')->with('resultCount',10)->with('fetchQues',null)->with('inactiveUsers', $inactiveUsers)->with('fetchQuesHist', null)->with('summary',null);
 		}
 	}

 	public function setManageAdvance(Request $data){

 		//fetching database catalogues
 		$years = DatabaseCatalogue::get()->pluck('year');
 		$inactiveUsers = Login::where('active',0)->count();
 		if(sizeof($years)>0)
 		{
	 		if(sizeof($data->all())>0){
	 			// return $data->all();
	 			
	 			$rules = [
		            'category' => 'integer|min:1|digits_between:1,4',
		            'difficulty' => 'integer|min:0|digits_between:0,3',
		            'year' => 'in:All,2014,2015,2016,2017,2018,2019,2020,2021,2022,2023,2024,2025',
		            'new' => 'in:next,previous,filter,goto',
		            'isSelected' => 'in:All,0,1',
		            'resultCount' => 'integer|min:10|max:100'
		        ];
		        $messages = [   
		            'category.integer' =>  'Category must be integer',
		            'difficulty.integer' =>  'Difficulty level must be integer',
		            'year.integer'        =>  'Year must be integer',
		            'new' =>  'Must be either previous, next, goto or filter',
		            'isSelected' => 'isSelected should be All or 0 or 1',
		            'resultCount' => 'Result count should be between 10 to 100'

		        ];
		        $validator = Validator::make($data->all(), $rules, $messages);
		        // return (string)$validator->fails();
		        if ($validator->fails()) {
		        	// return 'fail';
		            return redirect()->route('quesSetAdvance')->withErrors($validator)->with('inactiveUsers', $inactiveUsers);
		        }
		        if($data->new=='filter')
		        	Session::put('seed', time()); //seed
		        $category = $data -> category;
	 			$difficulty = $data -> difficulty;
	 			$year = $data -> year;
	 			$isSelected = $data -> isSelected;
	 			if($data -> isSelected == 'All')//include all yes or no
	 			{
	 				$data -> isSelected = [0,1];
	 			}
	 			else{
	 				$data -> isSelected = [$data -> isSelected];
	 			}
	 			if($data -> year == 'All')//include all years
	 			{
	 				$year = $years;
	 			}
	 			else{
	 				$year = [$data -> year];
	 			}
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
	 			$count = QuestionMaster::whereIn('year', $year)->whereIn('for_selectionTest', $data -> isSelected)->whereIn('category_id',$data -> category)->whereIn('difficulty_level',$data -> difficulty)->where('active', '=', 1)->count(); //added active question only
 				// $fetchQues = QuestionMaster::whereIn('year',$years)->where('active', '=', 1)->paginate(10);
 				$fetchQues = QuestionMaster::whereIn('year', $year)->whereIn('for_selectionTest', $data -> isSelected)->whereIn('category_id',$data -> category)->whereIn('difficulty_level',$data -> difficulty)->where('active', '=', 1)->inRandomOrder(Session::get('seed'))->paginate($data -> resultCount);
 				// return $fetchQues;
 				// return $data->all();
 				$summary = $this->getSummary();
				$apti = $summary[0];
				$elec = $summary[1];
				$prog = $summary[2];
				$summary = array('apti' => $apti, 'elec' => $elec, 'prog' => $prog);
 				return view('questions.setCreateAdvance')->with('userDetails', Auth::user())->with('years', $years)->with('defaultyear', $data -> year)->with('category', $category)->with('difficulty', $difficulty)->with('isSelected',$isSelected)->with('resultCount',$data -> resultCount)->with('fetchQues',$fetchQues)->with("count",$count)->with('inactiveUsers', $inactiveUsers)->with('summary',$summary);
	 		}
	 		
	 		$count = QuestionMaster::whereIn('year',$years)->where('active', '=', 1)->count(); //added active question only
	 		$fetchQues = QuestionMaster::whereIn('year', $years)->whereIn('for_selectionTest', [0,1])->whereIn('category_id',[1,2,3])->whereIn('difficulty_level',[0,1,2])->where('active', '=', 1)->inRandomOrder(Session::get('seed'))->paginate(10);
	 		$summary = $this->getSummary();
			$apti = $summary[0];
			$elec = $summary[1];
			$prog = $summary[2];
			$summary = array('apti' => $apti, 'elec' => $elec, 'prog' => $prog);
 			return view('questions.setCreateAdvance')->with('userDetails', Auth::user())->with('years', $years)->with('defaultyear', 'All')->with('category', 4)->with('difficulty', 3)->with('isSelected','All')->with('resultCount',10)->with('fetchQues',$fetchQues)->with("count",$count)->with('inactiveUsers', $inactiveUsers)->with('summary',$summary);
 		}
 			
 		else{//no database present 
 			return view('questions.setCreateAdvance')->with('userDetails', Auth::user())->with('defaultyear', null)->with('category', null)->with('difficulty', null)->with('isSelected','All')->with('resultCount',10)->with('fetchQues',null)->with('inactiveUsers', $inactiveUsers)->with('fetchQuesHist', null)->with('summary',null);
 		}
 	}


 	public function quesSelSave(Request $data){
 		if(sizeof($data->all())>0){
 			$rules = [
	            'action' => 'integer|min:0|max:1',
	            'qid' => 'integer|min:1'
	        ];
	        $messages = [   
	            'action' =>  'Action must be integer',
	            'qid' =>  'DQuestion id must be integer'

	        ];
	        $validator = Validator::make($data->all(), $rules, $messages);
	        // return (string)$validator->fails();
	        if ($validator->fails()) {
	        	// return 'fail';
	            return json_encode(['data' =>'Error']);
	        }
	        QuestionMaster::where('id', $data -> qid)->update(['for_selectionTest'=> $data->action]);
	        return json_encode(['data' =>'Success']);
 		}
 	}

 	public function refresh(Request $data){

 		$summary = $this->getSummary();
		$apti = $summary[0];
		$elec = $summary[1];
		$prog = $summary[2];
		$summary = array('apti' => $apti, 'elec' => $elec, 'prog' => $prog);
		return view('questions.helper.summary')->with('summary',$summary);
 	}

 	protected function getSummary()
 	{
 		// $Totalcount = QuestionMaster::whereIn('year', $year)->whereIn('for_selectionTest', [1])->where('active', '=', 1)->count();

 		$QuesStatsDiffEasy = QuestionMaster::whereIn('for_selectionTest', [1])->where('category_id', 1)->where('difficulty_level',0)->where('active', '=', 1)->count();//apti, tag easy
		$QuesStatsDiffMed = QuestionMaster::whereIn('for_selectionTest', [1])->where('category_id', 1)->where('difficulty_level',1)->where('active', '=', 1)->count();//apti, tag medium
		$QuesStatsDiffHard = QuestionMaster::whereIn('for_selectionTest', [1])->where('category_id', 1)->where('difficulty_level',2)->where('active', '=', 1)->count(); //apti, tag hard
		///////////
		/////////////
		$apti = [];
		$payload = array(
			'difficulty_level'=>'easy',
			'tag'=> $QuesStatsDiffEasy

		);
		array_push($apti, $payload);
		$payload = array(
			'difficulty_level'=>'medium',
			'tag'=> $QuesStatsDiffMed

		);
		array_push($apti, $payload);
		$payload = array(
			'difficulty_level'=>'hard',
			'tag'=> $QuesStatsDiffHard

		);
		array_push($apti, $payload);
		///////////////////////////////////////////////////////////
		$QuesStatsDiffEasy = QuestionMaster::whereIn('for_selectionTest', [1])->where('category_id', 2)->where('difficulty_level',0)->where('active', '=', 1)->count();//elec, tag easy
		$QuesStatsDiffMed = QuestionMaster::whereIn('for_selectionTest', [1])->where('category_id', 2)->where('difficulty_level',1)->where('active', '=', 1)->count();//elec,  tag medium
		$QuesStatsDiffHard = QuestionMaster::whereIn('for_selectionTest', [1])->where('category_id', 2)->where('difficulty_level',2)->where('active', '=', 1)->count(); //elec, tag hard
		/////////////
		$elec = [];
		$payload = array(
			'difficulty_level'=>'easy',
			'tag'=> $QuesStatsDiffEasy

		);
		array_push($elec, $payload);
		$payload = array(
			'difficulty_level'=>'medium',
			'tag'=> $QuesStatsDiffMed

		);
		array_push($elec, $payload);
		$payload = array(
			'difficulty_level'=>'hard',
			'tag'=> $QuesStatsDiffHard

		);
		array_push($elec, $payload);
		///////////////////////////////////////////////////////////
		$QuesStatsDiffEasy = QuestionMaster::whereIn('for_selectionTest', [1])->where('category_id', 3)->where('difficulty_level',0)->where('active', '=', 1)->count();//programming, tag easy
		$QuesStatsDiffMed = QuestionMaster::whereIn('for_selectionTest', [1])->where('category_id', 3)->where('difficulty_level',1)->where('active', '=', 1)->count();//programming, tag medium
		$QuesStatsDiffHard = QuestionMaster::whereIn('for_selectionTest', [1])->where('category_id', 3)->where('difficulty_level',2)->where('active', '=', 1)->count(); //programming, tag hard

		/////////////
		$prog = [];
		$payload = array(
			'difficulty_level'=>'easy',
			'tag'=> $QuesStatsDiffEasy

		);
		array_push($prog, $payload);
		$payload = array(
			'difficulty_level'=>'medium',
			'tag'=> $QuesStatsDiffMed

		);
		array_push($prog, $payload);
		$payload = array(
			'difficulty_level'=>'hard',
			'tag'=> $QuesStatsDiffHard

		);
		array_push($prog, $payload);
		// $prog = json_encode($prog);

		return array($apti, $elec, $prog);
 	}

 	protected function setCreate()
 	{
 		$QuesFetch = QuestionMaster::whereIn('for_selectionTest', [1])->where('active', '=', 1)->get();//
 		$QuesFetchArray = array('apti'=>array('easy'=>[], 'medium'=>[],'hard'=>[]), 'elec'=>array('easy'=>[], 'medium'=>[],'hard'=>[]), 'prog'=>array('easy'=>[], 'medium'=>[],'hard'=>[]));
 		$QuesApti = array('easy'=>[],'medium'=>[],'hard'=>[]);
 		$QuesElec = array('easy'=>[],'medium'=>[],'hard'=>[]);
 		$QuesProg = array('easy'=>[],'medium'=>[],'hard'=>[]);
 		foreach ($QuesFetch as $Ques) {
 			if($Ques->category_id==1)//apti
 			{
 				if ($Ques->difficulty_level==0)//easy
 				{
 					array_push($QuesApti['easy'],$Ques->id);

 				}
 				elseif ($Ques->difficulty_level==1) //medium
 				{
 					array_push($QuesApti['medium'],$Ques->id);

 				}
 				elseif ($Ques->difficulty_level==2) //hard
 				{
 					array_push($QuesApti['hard'],$Ques->id);

 				}

 			}
 			if($Ques->category_id==2)//elec
 			{
 				if ($Ques->difficulty_level==0)//easy
 				{
 					array_push($QuesElec['easy'],$Ques->id);

 				}
 				elseif ($Ques->difficulty_level==1) //medium
 				{
 					array_push($QuesElec['medium'],$Ques->id);

 				}
 				elseif ($Ques->difficulty_level==2) //hard
 				{
 					array_push($QuesElec['hard'],$Ques->id);

 				}
 				
 			}
 			if($Ques->category_id==3)//prog
 			{
 				if ($Ques->difficulty_level==0)//easy
 				{
 					array_push($QuesProg['easy'],$Ques->id);

 				}
 				elseif ($Ques->difficulty_level==1) //medium
 				{
 					array_push($QuesProg['medium'],$Ques->id);

 				}
 				elseif ($Ques->difficulty_level==2) //hard
 				{
 					array_push($QuesProg['hard'],$Ques->id);

 				}
 				
 			}
 		}
 		 	// shuffle
 		shuffle($QuesApti['easy']);
 		shuffle($QuesApti['medium']);
 		shuffle($QuesApti['hard']);

 		shuffle($QuesElec['easy']);
 		shuffle($QuesElec['medium']);
 		shuffle($QuesElec['hard']);

 		shuffle($QuesProg['easy']);
 		shuffle($QuesProg['medium']);
 		shuffle($QuesProg['hard']);

 		// start creating set
 		$aptieasy=0;
 		$eleceasy=0;
 		$progeasy=0;
		$aptimedium=0;
		$elecmedium=0;
		$progmedium=0;
		$aptihard=0;
		$elechard=0;
		$proghard=0;
		$i = 1;
		TestSetMaster::truncate(); // emptying the table
		TestSetQuestionMap::truncate();
 		for (;$i<=60;$i++)
 		{
 			$setname = "UNIQUE SET ".$i;
 			TestSetMaster::insert(['set_name'=> $setname]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesApti['easy'][$aptieasy++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesApti['easy'][$aptieasy++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesApti['easy'][$aptieasy++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesApti['medium'][$aptimedium++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesApti['medium'][$aptimedium++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesApti['medium'][$aptimedium++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesApti['medium'][$aptimedium++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesApti['hard'][$aptihard++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesApti['hard'][$aptihard++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesApti['hard'][$aptihard++], 'ques_type' => 0]);

 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesElec['easy'][$eleceasy++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesElec['easy'][$eleceasy++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesElec['easy'][$eleceasy++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesElec['medium'][$elecmedium++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesElec['medium'][$elecmedium++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesElec['medium'][$elecmedium++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesElec['medium'][$elecmedium++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesElec['hard'][$elechard++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesElec['hard'][$elechard++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesElec['hard'][$elechard++], 'ques_type' => 0]);

 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesProg['easy'][$progeasy++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesProg['easy'][$progeasy++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesProg['easy'][$progeasy++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesProg['medium'][$progmedium++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesProg['medium'][$progmedium++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesProg['medium'][$progmedium++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesProg['medium'][$progmedium++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesProg['hard'][$proghard++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesProg['hard'][$proghard++], 'ques_type' => 0]);
 			TestSetQuestionMap::insert(['set_id' => $i, 'ques_id' => $QuesProg['hard'][$proghard++], 'ques_type' => 0]);

 			// for ($j=1;$j<=10;$j++)//apti
 			// {

 			// }
 			// for ($j=1;$j<=10;$j++)//elec
 			// {

 			// }
 			// for ($j=1;$j<=10;$j++)//prog
 			// {

 			// }
 		}
 		if($i==61)
 			return json_encode(['data' =>'Success']);
 		else
 			return json_encode(['data' =>'Error']);


 	}

 	protected function getFileName($file)
	{
	   return str_random(20) . '.' . $file->extension();
	}
 }
