<?php

namespace App\Http\Controllers\Questions;
use Auth;
// use App\UserDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\QuestionMaster;
use App\DatabaseCatalogue;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller{

 	public function quesViewer(Request $data){

 		//fetching database catalogues
 		$years = DatabaseCatalogue::get()->pluck('year');
 		if(sizeof($years)>0)
 		{
	 		if(sizeof($data->all())>0)
	 		{	
	 			// return $data->all() ;
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
		            'current.max' =>  'Question id must be below 3000',
		            'current.min' =>  'Question id must be above 0'
		        ];
		        $validator = Validator::make($data->all(), $rules, $messages);
		        // return (string)$validator->fails();
		        if ($validator->fails()) {
		            return redirect()->route('quesViewer')->withErrors($validator);
		        }
	 			$count = QuestionMaster::where('year',$data -> year)->count();
	 			// return $data->all();
	 			$category = $data -> category;
	 			$difficulty = $data -> difficulty;
	 			if($data -> category = '4')//include all categories
	 			{
	 				$data -> category = [1,2,3];
	 			}
	 			else{
	 				$data -> category = [$data -> category];
	 			}
	 			if($data -> difficulty = '3')//include all categories
	 			{
	 				$data -> difficulty = [0,1,2];
	 			}
	 			else
	 			{
	 				$data -> difficulty = [$data -> difficulty];
	 			}
	 			if($data->new=="next")
					$fetchQues = QuestionMaster::where('year', $data -> year)->where('quid', '>', $data -> current)->whereIn('category_id',$data -> category)->whereIn('pre_tag',$data -> difficulty)->first();
				elseif ($data->new=="filter") {
					// return $data->all();
					$fetchQues = QuestionMaster::where('year', $data -> year)->whereIn('category_id',$data -> category)->whereIn('pre_tag',$data -> difficulty)->first();
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
						return view('questions.viewQuest')->with('userDetails', Auth::user())->with('years', $years)->with('defaultyear', $data -> year)->with('category', $category)->with('difficulty', $difficulty)->with('fetchQues',$fetchQues)->with('previous',$previous)->with('next',$next);
					}
				else//if no results than switch to default
				{
					$fetchQues = QuestionMaster::where('year', $data -> year)->where('quid', '=', $data -> current)->whereIn('category_id',$data -> category)->whereIn('pre_tag',$data -> difficulty)->first();
					if($fetchQues->quid == '1')
	 					$previous = 0;
		 			else
		 				$previous = $fetchQues->quid - 1;
		 			if($fetchQues->quid == $count)
		 				$next = 0;
		 			else
		 				$next = $fetchQues->quid + 1;
					return view('questions.viewQuest')->with('userDetails', Auth::user())->with('years', $years)->with('defaultyear', $data -> year)->with('category', $category)->with('difficulty', $difficulty)->with('fetchQues',$fetchQues)->with('previous',$previous)->with('next',$next);
				}
	 		}
	 		// if no request then do this
	 		$count = QuestionMaster::where('year',$years[sizeof($years)-1])->count();
 			$fetchQues = QuestionMaster::where('year',$years[sizeof($years)-1])->first();
 			if($fetchQues->quid == '1')
 				$previous = 0;
 			else
 				$previous = $fetchQues->quid - 1;
 			if($fetchQues->quid == $count)
 				$next = 0;
 			else
 				$next = $fetchQues->quid + 1;
 			return view('questions.viewQuest')->with('userDetails', Auth::user())->with('years', $years)->with('defaultyear', $years[sizeof($years)-1])->with('category', 4)->with('difficulty', 3)->with('fetchQues',$fetchQues)->with('previous',$previous)->with('next',$next);
 		}
 			
 		else
 			return view('questions.viewQuest')->with('userDetails', Auth::user())->with('defaultyear', null)->with('category', null)->with('difficulty', null)->with('fetchQues',null);
 	}
 	public function quesEditor(){
 		return view('questions.addQuest')->with('userDetails', Auth::user());
 	}
 	public function showStats(){
 		return view('questions.showStat')->with('userDetails', Auth::user());
 	}
 	public function showTasks(){
 		return view('questions.taskViewer')->with('userDetails', Auth::user());
 	}
}
