<?php

namespace SlimKit\PlusQuestion\Admin\Controllers;

use Illuminate\Http\Request;
use SlimKit\PlusQuestion\Models\Answer as AnswerModel;
use SlimKit\PlusQuestion\Models\Question as QuestionModel;

class AnswerController extends Controller
{
    /**
     * Get answers list.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request)
    {
        $question = $request->query('question');
        $id = $request->query('id');
        $user = $request->query('id');
        $type = $request->query('type', false);
        $reward = $request->query('reward', false);
        $start_date = $request->query('start_date');
        $end_date = $request->query('start_date');
        $trash = $request->query('trash');
        $limit = (int) $request->query('limit', 15);
        $offset = (int) $request->query('offset', 0);

        if ($id) {
            return resonse()->json([AnswerModel::find($id)], 200);
        }

        $query = AnswerModel::query();
        if ($question) {
            if (! ($question = QuestionModel::find($question))) {
                return response()->json([], 200);
            }

            $query = $question->answers();
        }

        $query = $query->when($trash, function ($query) {
            return $query->onlyTrashed();
        })
        ->when($user, function ($query) use ($user) {
            return $query->where('user_id', $user);
        })
        ->when($type === '1', function ($query) {
            return $query->where('invited', 1);
        })
        ->when($type === '2', function ($query) {
            return $query->where('adoption', 1);
        })
        ->when($type === '3', function ($query) {
            return $query->where('adoption', 0)->where('invited', 0);
        })
        ->when($reward === '1', function ($query) {
            return $query->where('rewards_amount', '!=', 0);
        })
        ->when($reward === '0', function ($query) {
            return $query->where('rewards_amount', 0);
        })
        ->when($start_date, function ($query) use ($start_date) {
            return $query->whereDate('created_at', '>=', $start_date);
        })
        ->when($end_date, function ($query) use ($end_date) {
            return $query->whereDate('created_at', '<=', $end_date);
        });

        $total = $query->count('id');
        $answers = $query->limit($limit)
            ->offset($offset)
            ->orderBy('id', 'desc')
            ->get();
        $answers->load([
            'user',
            'question' => function ($query) {
                $query->select(['id', 'subject']);
            }
        ]);

        return response()->json($answers, 200, ['x-total' => $total]);
    }

    /**
     * Destroy a answer.
     *
     * @param \SlimKit\PlusQuestion\Models\Answer $answer
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function destroy(AnswerModel $answer)
    {
        $answer->delete();

        if ($question = $answer->question) {
            $question->decrement('answers_count', 1);
        }

        return response('', 204);
    }

    /**
     * Restore a answer.
     *
     * @param int $answer
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function restore(int $answer)
    {
        AnswerModel::withTrashed()
            ->where('id', $answer)
            ->restore();
        $answer = AnswerModel::find($answer);
        if ($question = $answer->question) {
            $question->increment('answers_count', 1);
        }

        return response('', 204);
    }
}
