<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Config;

class ConstantAPIController extends Controller
{
    public function get() {


    	return Response::json([
    		'question_status' => Config::get('constants.QUESTION_STATUS'),
    		'vote_category' => Config::get('constants.VOTE_CATEGORY'),
    		'vote_type' => Config::get('constants.VOTE_TYPE'),
    	]);
    }
}
