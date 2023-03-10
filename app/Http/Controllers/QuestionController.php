<?php

namespace App\Http\Controllers;


use App\Http\Requests\CreateQuestionRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Tag;
use App\Models\UserVote;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Inertia\Inertia;


class QuestionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function showAsk()
    {
        if (Auth::user()) {
            return Inertia::render('Question/AskQuestion');
        } else {
            return redirect("/login");
        }
    }
    
    public function ask(CreateQuestionRequest $request)
    {
        
        $question = new Question;
        
        $question->title = Request::get('title');
        
        $question->slug = Str::slug($question->title, '-');
        
        $question->content = Request::get('content');
        
        $question->status = Config::get('constants.QUESTION_STATUS.ACTIVE');
        
        $question->created_by = Auth::user()->id;
        
        $question->save();
        
        foreach (explode(',', strtolower(Request::get('tag'))) as $key => $value) {
            $tag = new Tag;
            $tag->name = $value;
            $tag->question_id = $question->id;
            $tag->save();
        }
        
        $question_url = Config::get('constants.QUESTION_URL') . '/' . $question->id . '/' . $question->slug;
        
        
        return redirect($question_url)->with('success', 'Question Added.');
    }
    
    public function answer()
    {
        
        $question = Question::find(Request::get("question_id"));
        
        $answer = new Answer;
        $answer->content = Request::get("answer");
        $answer->question_id = $question->id;
        $answer->answer_id = null;
        $answer->status = 1;
        $answer->created_by = Auth::user()->id;
        $answer->save();
        
        $question_url = Config::get('constants.QUESTION_URL') . '/' . $question->id . '/' . $question->slug;
        
        return redirect($question_url);
    }
    
    public function comment(Question $question, Answer $ans = null)
    {
        
        $answer = new Answer;
        $answer->content = Request::get("content");
        $answer->question_id = $question->id;
        $answer->answer_id = $ans ? $ans->id : null;
        $answer->status = 1;
        $answer->created_by = Auth::user()->id;
        $answer->save();
        
        return Response::json(['status' => true, 'answer' => $answer]);
        
    }
    
    /**
     * TODO validate request
     * @param Question $question
     * @return \Illuminate\Http\JsonResponse
     */
    public function addNewComment(Question $question)
    {
        
        $answer = Answer::create([
            'content' => Request::get("content"),
            'question_id' => $question->id,
            'created_by' => Auth::user()->id,
            'status' => 1
        ]);
        
        return Response::json(['status' => true, 'answer' => $answer]);
        
    }
    
    /**
     * TODO validate request
     * @param Question $question
     * @return \Illuminate\Http\JsonResponse
     */
    public function addNewAnswerComment(Question $question, Answer $answer)
    {
        
        $answer = Answer::create([
            'content' => Request::get("content"),
            'question_id' => $question->id,
            'answer_id' => $answer->id,
            'created_by' => Auth::user()->id,
            'status' => 1
        ]);
        
        return Response::json(['status' => true, 'answer' => $answer]);
        
    }
    
    
    public function getQuestionById($id)
    {
        $question = Question::find($id);
        $question->asked_user = ($question->user)['name'];
        $question->asked_user_id = ($question->user)['id'];
        
        $question->tags = $question->tag;
        $question->asked_user_avatar = '/uploads/avatars/' . ($question->user)['avatar'];
        
        if (Auth::user()) {
            $question->up_voted = $this->findUserVoteByType($id, Config::get('constants.VOTE_CATEGORY.QUESTION'), Config::get('constants.VOTE_TYPE.UP_VOTE'));
            $question->down_voted = $this->findUserVoteByType($id, Config::get('constants.VOTE_CATEGORY.QUESTION'), Config::get('constants.VOTE_TYPE.DOWN_VOTE'));
        } else {
            $question->up_voted = false;
            $question->down_voted = false;
        }
        
        $question->formatted_created_at = $question->created_at->format('M-d-Y') . ' at ' . $question->created_at->format('h:i');
        
        $question->votes = $this->countUpVoteByVoteId($id, Config::get('constants.VOTE_CATEGORY.QUESTION'))
            - $this->countDownVoteByVoteId($id, Config::get('constants.VOTE_CATEGORY.QUESTION'));
        return Response::json($question);
    }
    
    public function getAnswersById($questionId)
    {
        
        $answers = Answer::with(array('children' => function ($query) {
            $query
                ->with(array('user' => function ($query1) {
                    $query1->select('*');
                }))->select('*');
        }))
            ->with(array('user' => function ($query) {
                $query->select('*');
            }))
            ->where('answers.question_id', $questionId)
            ->whereNull('answer_id')
            ->orderBy('answers.status', 'desc')
            ->orderBy('answers.created_at', 'desc')
            ->get();
        
        foreach ($answers as $key => $answer) {
            
            if (Auth::user()) {
                $answers[$key]->up_voted = $this->findUserVoteByType($answer->id, Config::get('constants.VOTE_CATEGORY.ANSWER'), Config::get('constants.VOTE_TYPE.UP_VOTE'));
                $answers[$key]->down_voted = $this->findUserVoteByType($answer->id, Config::get('constants.VOTE_CATEGORY.ANSWER'), Config::get('constants.VOTE_TYPE.DOWN_VOTE'));
            } else {
                $answers[$key]->up_voted = false;
                $answers[$key]->down_voted = false;
                
            }
            $answers[$key]->show_comment = false; //
            
            $answers[$key]->formatted_created_at = $answers[$key]->created_at->format('M-d-Y') . ' at ' . $answers[$key]->created_at->format('h:i');
            
            $answers[$key]->votes = $this->countUpVoteByUserAndVoteId($answer->id, ($answer->user)['id'], Config::get('constants.VOTE_CATEGORY.ANSWER'))
                - $this->countDownVoteByUserAndVoteId($answer->id, ($answer->user)['id'], Config::get('constants.VOTE_CATEGORY.ANSWER'));
        }
        
        Log::info(json_encode($answers, true));
        
        return Response::json($answers);
    }
    
    public function showQuestionList()
    {
        
        $questions = Question::orderBy('created_at', 'desc')->paginate(3);
        
        $newest_questions = Question::orderBy('created_at', 'desc')->paginate(8);
        
        $question_count = Question::count();
        
        foreach ($questions as $key => $question) {
            $questions[$key]->votes = $this->countUpVoteByVoteId($question->id, Config::get('constants.VOTE_CATEGORY.QUESTION'))
                - $this->countDownVoteByVoteId($question->id, Config::get('constants.VOTE_CATEGORY.QUESTION'));
            
            $questions[$key]->answers = $this->countAnswerByQuestionId($question->id);
            $questions[$key]->tags = $question->tag;
            $questions[$key]->asked_user_avatar = '/uploads/avatars/' . ($question->user)['avatar'];
            $questions[$key]->asked_user = $question->user;
            
        }
        
        $top_tags = $this->getTopTags();
        
        $questionList = [
            'questions' => $questions,
            'newest_questions' => $newest_questions,
            'question_count' => $question_count,
            'top_tags' => $top_tags
        ];
        
        return Inertia::render('Question/QuestionList', $questionList);
    }

//    public function showQuestionList()
//    {
//
//        $questions = Question::orderBy('created_at', 'desc')->paginate(10);
//
//        $newest_questions = Question::orderBy('created_at', 'desc')->paginate(20);
//
//        $question_count = Question::count();
//
//        foreach ($questions as $key => $question) {
//            $questions[$key]->votes = $this->countUpVoteByVoteId($question->id, Config::get('constants.VOTE_CATEGORY.QUESTION'))
//                - $this->countDownVoteByVoteId($question->id, Config::get('constants.VOTE_CATEGORY.QUESTION'));
//
//            $questions[$key]->answers = $this->countAnswerByQuestionId($question->id);
//
//        }
//
//        $top_tags = $this->getTopTags();
//
//        return view('question.list', ['questions' => $questions, 'newest_questions' => $newest_questions
//            , 'question_count' => $question_count, 'top_tags' => $top_tags]);
//    }
    
    public function showEditAnswer($id)
    {
        
        $answer = Answer::find($id);
        
        if (!Auth::check() || (Auth::user()->id != $answer->created_by)) {
            return redirect('/questions/list');
        }
        
        return view('question.edit_answer', ['answer' => $answer]);
    }
    
    public function showEditQuestion($id)
    {
        
        $question = Question::find($id);
        
        if (!Auth::check() || (Auth::user()->id != $question->created_by)) {
            return redirect('/questions/list');
        }
        
        $tags = [];
        
        foreach ($question->tag as $key => $value) {
            $tags[] = $value->name;
        }
        $question->tags = implode(',', $tags);
        return view('question.edit_question', ['question' => $question]);
    }
    
    public function editQuestion()
    {
        
        $id = Request::get('id');
        
        $question = Question::find($id);
        
        $question->title = Request::get('title');
        
        $question->slug = str_slug($question->title, '-');
        
        $question->content = Request::get('content');
        
        $question->save();
        
        foreach ($question->tag as $key => $value) {
            $tag = Tag::find($value->id);
            $tag->delete();
        }
        foreach (explode(',', strtolower(Request::get('tag'))) as $key => $value) {
            $tag = new Tag;
            $tag->name = $value;
            $tag->question_id = $question->id;
            $tag->save();
        }
        
        $question_url = Config::get('constants.QUESTION_URL') . '/' . $question->id . '/' . $question->slug;
        
        return redirect($question_url);
        
    }
    
    public function editAnswer()
    {
        $id = Request::get('id');
        
        $answer = Answer::find($id);
        $answer->content = Request::get('content');
        $answer->save();
        
        $question = Question::find($answer->question_id);
        $question_url = Config::get('constants.QUESTION_URL') . '/' . $question->id . '/' . $question->slug;
        
        return redirect($question_url);
        
    }
    
    public function showQuestionByTag($tag)
    {
        $questions = Question::whereHas('tag', function ($query) use ($tag) {
            $query->where('name', '=', $tag);
        })
            ->orderBy('created_at', 'desc')->paginate(8);
        
        $newest_questions = Question::orderBy('created_at', 'desc')->paginate(8);
        
        $question_count = Question::count();
        
        foreach ($questions as $key => $question) {
            $questions[$key]->votes = $this->countUpVoteByVoteId($question->id, Config::get('constants.VOTE_CATEGORY.QUESTION'))
                - $this->countDownVoteByVoteId($question->id, Config::get('constants.VOTE_CATEGORY.QUESTION'));
            
            $questions[$key]->answers = $this->countAnswerByQuestionId($question->id);
            $questions[$key]->tags = $question->tag;
            $questions[$key]->asked_user_avatar = '/uploads/avatars/' . ($question->user)['avatar'];
            $questions[$key]->asked_user = $question->user;
            
        }
        
        $top_tags = $this->getTopTags();
        
        $questionList = [
            'questions' => $questions,
            'newest_questions' => $newest_questions,
            'question_count' => $question_count,
            'top_tags' => $top_tags
        ];
        
        return Inertia::render('Question/QuestionList', $questionList);
//
//        $newest_questions = Question::orderBy('created_at', 'desc')->paginate(20);
//
//        $question_count = Question::count();
//
//        foreach ($questions as $key => $question) {
//            $questions[$key]->votes = $this->countUpVoteByVoteId($question->id, Config::get('constants.VOTE_CATEGORY.QUESTION'))
//                - $this->countDownVoteByVoteId($question->id, Config::get('constants.VOTE_CATEGORY.QUESTION'));
//
//            $questions[$key]->answers = $this->countAnswerByQuestionId($question->id);
//        }
//
//        $top_tags = $this->getTopTags();
//
//        $questionList = [
//            'questions' => $questions,
//            'newest_questions' => $newest_questions,
//            'question_count' => $question_count,
//            'top_tags' => $top_tags
//        ];
//
//        return Inertia::render('Question/QuestionList', $questionList);
        
    }
    
    
    public function showQuestionDetail($id, $slug)
    {
        
        $currentUserId = 0;
        
        if (Auth::user()) {
            $currentUserId = Auth::user()->id;
        }
        
        $question = Question::find($id);
        
        if ($question) {
            $question->views++;
            $question->save();
        }
        
        $newest_questions = Question::orderBy('created_at', 'desc')->paginate(20);
        
        $questionList = ['question' => $question,
            'newest_questions' => $newest_questions,
            'currentUserId' => $currentUserId,
            'isLogin' => $currentUserId > 0];
        
        return Inertia::render('Question/QuestionDetail', $questionList);
    }
    
    protected function findUserVote($vote_id, $vote_category)
    {
        $user_vote = UserVote::where('vote_id', $vote_id)
            ->where('vote_by', Auth::user()->id)
            ->where('vote_category', $vote_category) // 0 question, 1 answer
            // ->where('vote_type', $vote_type)    // 0 vote, 1 downvote
            ->first();
        return $user_vote;
    }
    
    protected function findUserVoteByType($vote_id, $vote_category, $vote_type)
    {
        
        $user_vote = UserVote::where('vote_id', $vote_id)
            ->where('vote_by', Auth::user()->id)
            ->where('vote_category', $vote_category)
            ->where('vote_type', $vote_type)
            ->exists();
        return $user_vote;
    }
    
    protected function countUpVoteByUserAndVoteId($vote_id, $user_id, $vote_category)
    {
        $up_vote = UserVote::where('vote_id', $vote_id)
            // ->where('vote_by', $user_id)
            ->where('vote_type', Config::get('constants.VOTE_TYPE.UP_VOTE'))
            ->where('vote_category', $vote_category)
            ->count();
        return $up_vote;
    }
    
    protected function countDownVoteByUserAndVoteId($vote_id, $user_id, $vote_category)
    {
        $up_vote = UserVote::where('vote_id', $vote_id)
            // ->where('vote_by', $user_id)
            ->where('vote_type', Config::get('constants.VOTE_TYPE.DOWN_VOTE'))
            ->where('vote_category', $vote_category)
            ->count();
        return $up_vote;
    }
    
    protected function countUpVoteByVoteId($vote_id, $vote_category)
    {
        $up_vote = UserVote::where('vote_id', $vote_id)
            ->where('vote_type', Config::get('constants.VOTE_TYPE.UP_VOTE'))
            ->where('vote_category', $vote_category)
            ->count();
        return $up_vote;
    }
    
    protected function countDownVoteByVoteId($vote_id, $vote_category)
    {
        $up_vote = UserVote::where('vote_id', $vote_id)
            ->where('vote_type', Config::get('constants.VOTE_TYPE.DOWN_VOTE'))
            ->where('vote_category', $vote_category)
            ->count();
        return $up_vote;
    }
    
    protected function countAnswerByQuestionId($questionId)
    {
        
        $answers = Answer::where('answers.question_id', $questionId)->whereNull('answer_id')
            ->count();
        return $answers;
    }
    
    protected function getTopTags()
    {
        
        $tags = DB::table('tags')
            ->select('name', DB::raw('count(question_id) as total'))
            ->groupBy('name')
            ->orderBy('total', 'desc')
            ->paginate(10);
        
        return $tags;
    }
    
    protected function setAllAnswersAcceptedToFalse($question_id)
    {
        Answer::where('answers.question_id', $question_id)
            ->update(['accepted' => false]);
    }
    
    protected function undoVoted($user_voted)
    {
        $user_voted->delete();
    }
    
    protected function insertUserVote($vote_id, $vote_category, $vote_type)
    {
        
        $new_vote = new UserVote;
        $new_vote->created_at = Carbon::now();
        $new_vote->vote_by = Auth::user()->id;
        $new_vote->vote_id = $vote_id;
        $new_vote->vote_category = $vote_category;
        $new_vote->vote_type = $vote_type;
        $new_vote->save();
        
    }
    
    public function voteAction()
    {
        $vote_id = Request::get('vote_id');
        $vote_type = Request::get('vote_type');
        $vote_category = Request::get('vote_category');
        
        $user_voted = $this->findUserVote($vote_id, $vote_category);
        
        if ($user_voted) {
            
            $isUndoVoted = $user_voted->vote_type == $vote_type;
            
            if ($isUndoVoted) {
                $this->undoVoted($user_voted);
            } else {
                $this->undoVoted($user_voted);
                $this->insertUserVote($vote_id, $vote_category, $vote_type);
            }
            
        } else {
            $this->insertUserVote($vote_id, $vote_category, $vote_type);
        }
        
        $up_votes = $this->countUpVoteByUserAndVoteId($vote_id, Auth::user()->id, $vote_category);
        $down_votes = $this->countDownVoteByUserAndVoteId($vote_id, Auth::user()->id, $vote_category);
        
        $votes = $up_votes - $down_votes;
        $up_voted = $this->findUserVoteByType($vote_id, $vote_category, Config::get('constants.VOTE_TYPE.UP_VOTE'));
        $down_voted = $this->findUserVoteByType($vote_id, $vote_category, Config::get('constants.VOTE_TYPE.DOWN_VOTE'));
        
        return Response::json(['status' => true, 'votes' => $votes, 'up_voted' => $up_voted, 'down_voted' => $down_voted
        ]);
    }
    
    public function acceptAnswer()
    {
        $answer_id = Request::get('answer_id');
        $question_id = Request::get('question_id');
        
        $question = Question::find($question_id);
        $anwser = Answer::find($answer_id);
        
        if (Auth::user()->id != $question->created_by) {
            return Response::json(['status' => false]);
        }
        
        $this->setAllAnswersAcceptedToFalse($question_id);
        
        $anwser->accepted = true;
        $anwser->save();
        
        
        return Response::json(['status' => true]);
    }
}
