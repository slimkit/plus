<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use Illuminate\Http\Request;
use SlimKit\PlusQuestion\Models\Topic as TopicModel;
use SlimKit\PlusQuestion\Models\Question as QuestionModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class TopicQuestionController extends Controller
{
    /**
     * List all question for topic.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \SlimKit\PlusQuestion\Models\Topic $topic
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request,
                          ResponseFactoryContract $response,
                          TopicModel $topic)
    {
        $userID = $request->user('api')->id ?? 0;
        $limit = max(1, min(30, $request->query('limit', 15)));
        $offset = max(0, $request->query('offset', 0));
        $subject = $request->query('subject');
        $map = [
            'all' => function ($query) {
                $query->orderBy('id', 'desc');
            },
            'new' => function ($query) {
                $query->where('answers_count', 0)
                    ->orderBy('id', 'desc');
            },
            'hot' => function ($query) use ($topic) {
                $query->whereBetween('created_at', [
                    $topic->freshTimestamp()->subMonth(1),
                    $topic->freshTimestamp(),
                ]);
                $query->orderBy('answers_count', 'desc');
            },
            'reward' => function ($query) {
                $query->where('amount', '!=', 0)
                    ->orderBy('id', 'desc');
            },
            'excellent' => function ($query) {
                $query->where('excellent', '!=', 0)
                    ->orderBy('id', 'desc');
            },
        ];
        $type = in_array($type = $request->query('type', 'new'), array_keys($map)) ? $type : 'new';
        call_user_func($map[$type], $query = $topic->questions()->with('user'));
        $questions = $query->limit($limit)
            ->when($subject, function ($query) use ($subject) {
                return $query->where('subject', 'like', '%'.$subject.'%');
            })
            ->offset($offset)
            ->get();

        return $response->json($questions->map(function (QuestionModel $question) use ($userID) {
            if ($question->anonymity) {
                $question->addHidden('user');
                $question->user_id = 0;
            }

            $question->answer = $question->answers()
                ->with('user')
                ->orderBy('id', 'desc')
                ->first();

            if ($question->answer) {
                if ($question->answer->anonymity && $question->answer->user_id !== $userID) {
                    $question->answer->addHidden('user');
                    $question->answer->user_id = 0;
                }
                $question->answer->liked = (bool) $question->answer->liked($userID);
                $question->answer->collected = (bool) $question->answer->collected($userID);
                $question->answer->rewarded = (bool) $question->answer->rewarders()->where('user_id', $userID)->first();
                $question->look && $question->answer->could = true;

                if ($question->look && $question->answer->invited && (! $question->answer->onlookers()->where('user_id', $userID)->first()) && $question->answer->user_id !== $userID && $question->user_id !== $userID) {
                    $question->answer->could = false;
                    $question->answer->body = null;
                }
            }

            return $question;
        }))->setStatusCode(200);
    }
}
