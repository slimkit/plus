<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use SlimKit\PlusQuestion\Models\Question;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;

class QuestionController extends BaseController
{
    /**
     * 问答
     * @author 28youth
     * @param  Request $request
     * @return mixed
     */
    public function question(Request $request)
    {
        $this->PlusData['current'] = 'question';
        if ($request->isAjax){
            $params = [
                'offset' => $request->query('offset', 0),
                'limit' => $request->query('limit', 0),
                'type' => $request->query('type', 'all'),
            ];
            $question['data'] = api('GET', '/api/v2/questions', $params);
            if ($params['type'] == 'excellent') {
                // TODO
                foreach ($question['data'] as $key => &$value) {
                    $value->excellent_show = false;
                }
            }
            $html = view('pcview::templates.question', $question, $this->PlusData)->render();

            return response()->json([
                'status' => true,
                'data' => $html
            ]);
        }

        return view('pcview::question.index', [], $this->PlusData);
    }

    /**
     * 专题
     * @author 28youth
     * @param  Request $request
     * @return mixed
     */
    public function topic(Request $request)
    {
        if ($request->isAjax){
            $type = $request->query('type', 'all');
            switch ($type) {
                case 'all':
                    $params = [
                        'offset' => $request->query('offset', 0),
                        'limit' => $request->query('limit', 10),
                        'follow' => $request->query('follow', 1),
                    ];
                    $data['data'] = api('GET', '/api/v2/question-topics', $params);
                    $after = '';
                    break;
                case 'follow':
                    $params = [
                        'after' => $request->query('after', 0),
                        'limit' => $request->query('limit', 10),
                    ];
                    $questions = api('GET', '/api/v2/user/question-topics', $params);
                    // TODO
                    foreach ($questions as $key => &$value) {
                        $value['has_follow'] = true;
                    }
                    $after = last($questions)['id'] ?? 0;
                    $data['data'] = $questions;
                    break;
            }
            $html = view('pcview::templates.question_topic', $data, $this->PlusData)->render();

            return response()->json([
                'status' => true,
                'after' => $after,
                'data' => $html
            ]);
        }

        return view('pcview::question.topic', [], $this->PlusData);
    }

    /**
     * 专题详情.
     * @author ZsyD
     * @param Request $request
     * @param int $topic [专题id]
     * @return mixed
     */
    public function topicInfo(Request $request, int $topic)
    {
        if ($request->isAjax){
            $this->PlusData['current'] = 'question';
            $type = $request->query('type', 'hot');
            $topic_id = $request->input('topic_id');
            $params = [
                'offset' => $request->input('offset', 0),
                'limit' => $request->input('limit', 10),
                'type' => $request->input('type', $type),
            ];
            $question['data'] = api('GET', '/api/v2/question-topics/'.$topic_id.'/questions', $params);
            if ($params['type'] == 'excellent') {
                // TODO
                foreach ($question['data'] as $key => &$value) {
                    $value['excellent_show'] = false;
                }
            }
            $html = view('pcview::templates.question', $question, $this->PlusData)->render();

            return response()->json([
                'status' => true,
                'data' => $html
            ]);
        }
        $data['topic'] = api('GET', '/api/v2/question-topics/'.$topic );
        $data['experts'] = api('GET', '/api/v2/question-topics/'.$topic . '/experts');

        return view('pcview::question.topic_info', $data, $this->PlusData);
    }

    /**
     * 问题详情
     * @author ZsyD
     * @param  int    $question [问题id]
     * @return mixed
     */
    public function read(int $question)
    {
        $this->PlusData['current'] = 'question';

        $data['question'] = api('GET', '/api/v2/questions/'.$question);

        return view('pcview::question.read', $data, $this->PlusData);

    }

    /**
     * 回答详情
     * @author ZsyD
     * @param  int    $question [问题id]
     * @param  int    $answer   [回答id]
     * @return mixed
     */
    public function answer(int $question, int $answer)
    {
        $answer = api('GET', '/api/v2/question-answers/'.$answer );
        $data['answer'] = $answer;
        return view('pcview::question.answer', $data, $this->PlusData);
    }

    /**
     * [回答评论列表]
     * @author ZsyD
     * @param  Request $request
     * @param  int     $answer  [回答id]
     * @return \Illuminate\Http\JsonResponse
     */
    public function answerComments(Request $request, int $answer)
    {
        $params = [
            'after' => $request->query('after', 0),
            'limit' => $request->query('limit', 10),
        ];
        $comments = api('GET', '/api/v2/question-answers/'.$answer.'/comments', $params);
        $after = last($comments)['id'] ?? 0;
        $data['comments'] = $comments;
        $data['top'] = false;

        $html = view('pcview::templates.comment', $data, $this->PlusData)->render();

        return response()->json([
            'status' => true,
            'after' => $after,
            'data' => $html
        ]);
    }

    /**
     * 创建/修改问题
     * @author ZsyD
     * @param  Request     $request
     * @param  int|integer $question_id [问题id]
     * @return [type]                   [description]
     */
    public function createQuestion(Request $request, int $question_id = 0)
    {
        if ($topic_id = $request->query('topic_id')) {
            $data['topic'] = api('GET', '/api/v2/question-topics/'.$topic_id );
        }

        if ($question_id > 0) {
            $data['question'] = api('GET', '/api/v2/questions/'.$question_id );
        }

        $data['topics'] = api('GET', '/api/v2/question-topics');

        return view('pcview::question.create_question', $data, $this->PlusData);
    }

    /**
     * 邀请用户
     * @author ZsyD
     * @param  Request $request
     * @return mixed
     */
    public function getUsers(Request $request)
    {
        $ajax = $request->input('ajax');
        $params['limit'] = $request->input('limit') ?: 10;
        $params['topics'] = (string) $request->input('topics');
        $params['keyword'] = $request->input('keyword') ?: '';
        $data['topics'] = $params['topics'];
        if ($ajax == 1) {
            $search = $request->input('search');
            if ($search == 1) {
                $url = '/api/v2/user/search';
            } else {
                $url = '/api/v2/question-experts';
            }
            $data['users'] = api('GET', $url, $params);
            $return = view('pcview::question.user_list', $data)
                ->render();

            return response()->json([
                'status'  => true,
                'data' => $return,
            ]);
        } else {
            return view('pcview::question.users', $data, $this->PlusData);
        }
    }

    /**
     * 回答列表
     * @author ZsyD
     * @param  Request $request
     * @param  Question $question
     * @return mixed
     */
    public function getAnswers(Request $request, Question $question)
    {
        $params['limit'] = $request->input('limit') ?: 10;
        $params['offset'] = $request->input('offset') ?: 0;
        $params['order_type'] = $request->input('order_type') ?: 'time';
        $question = api('GET', '/api/v2/questions/' . $question->id);
        $data['answers'] = api('GET', '/api/v2/questions/' . $question['id'] . '/answers', $params);
        if ($params['offset'] == 0) {
            if (!empty($question['adoption_answers'])) { // 采纳回答
                foreach ($question['adoption_answers'] as $key => $item) {
                    array_unshift($data['answers'], $item);
                }
            }
            if (!empty($question['invitation_answers'])) { // 悬赏人回答
                foreach ($question['invitation_answers'] as $key => $item) {
                    array_unshift($data['answers'], $item);
                };
            }
        }
        $data['question'] = $question;

        $return = view('pcview::question.question_answer', $data, $this->PlusData)
            ->render();

        return response()->json([
            'status'  => true,
            'data' => $return,
        ]);
    }

    /**
     * 问题评论列表
     * @author ZsyD
     * @param  Request $request
     * @param  int     $question [问题id]
     * @return \Illuminate\Http\JsonResponse
     */
    public function questionComments(Request $request, int $question)
    {
        $params = [
            'after' => $request->query('after', 0),
            'limit' => $request->query('limit', 10),
        ];
        $comments = api('GET', '/api/v2/questions/'.$question.'/comments', $params);
        $after = last($comments)['id'] ?? 0;
        $data['comments'] = $comments;
        $data['top'] = false;
        $html = view('pcview::templates.comment', $data, $this->PlusData)->render();

        return response()->json([
            'status' => true,
            'after' => $after,
            'data' => $html
        ]);
    }

    /**
     * 专题-更多专家列表
     * @author ZsyD
     * @param Request $request
     * @param int $topic [专题id]
     * @return mixed
     */
    public function topicExpert(Request $request, int $topic)
    {
        if ($request->isAjax) {
            $limit = $request->input('limit') ?: 18;
            $after = $request->input('after') ?: 0;
            $params = [
                'limit' => $limit,
                'after' => $after,
            ];
            $data['users'] = api('GET', '/api/v2/question-topics/'.$topic.'/experts', $params);
            $after = $data['users']->pop()->id ?? 0;

            $html =  view('pcview::templates.user', $data, $this->PlusData)->render();

            return response()->json([
                'status'  => true,
                'data' => $html,
                'after' => $after
            ]);
        }

        $this->PlusData['current'] = 'question';
        $data['topic'] = $topic;

        return view('pcview::question.topic_experts', $data, $this->PlusData);
    }

    /**
     * 答案编辑
     * @author ZsyD
     * @param  Request $request
     * @param  int     $answer  [答案id]
     * @return mixed
     */
    public function editAnswer(Request $request, int $answer)
    {
        $answer = api('GET', '/api/v2/question-answers/'.$answer );
        $data['answer'] = $answer;

        return view('pcview::question.answer_edit', $data, $this->PlusData);
    }
}
