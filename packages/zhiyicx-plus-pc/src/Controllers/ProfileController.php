<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;
    
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\User as UserModel;
use function zhiyi\Component\ZhiyiPlus\PlusComponentPc\replaceUrl;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;

class ProfileController extends BaseController
{
    /**
     * 动态
     * @author Foreach
     * @param  Request     $request
     * @param  int|integer $user_id [用户id]
     * @return mixed
     */
    public function feeds(Request $request, UserModel $user)
    {
        $this->PlusData['current'] = 'feeds';
        if ($request->isAjax) {
            $params = [
                'type' => $request->query('type'),
                'user' => $request->query('user'),
                'after' => $request->query('after', 0),
            ];
            $cate = $request->query('cate', 1);
            switch ($cate) {
                case 1: //全部
                    $feeds = api('GET', '/api/v2/feeds', $params);
                    $feed = clone $feeds['feeds'];
                    $after = $feed->pop()->id ?? 0;
                    $feeds['conw'] = 735;
                    $feeds['conh'] = 545;
                    $html = view('pcview::templates.feeds', $feeds, $this->PlusData)->render();
                    break;
                default:
                    # code...
                    break;
            }

            return response()->json([
                'status' => true,
                'after' => $after,
                'data' => $html
            ]);
        }
        $user = $user->id ? $user : $request->user();
        $user->follower = $user->hasFollower($request->user()->id);

        return view('pcview::profile.index', compact('user'), $this->PlusData);
    }

    /**
     * 文章.
     * @author 28youth
     * @param  Request $request
     * @return mixed
     */
    public function news(Request $request, UserModel $user)
    {
        $this->PlusData['current'] = 'news';
        if ($request->isAjax) {
            $params = [
                'type' => $request->query('type'),
                'after' => $request->query('after', 0)
            ];
            if ($request->query('user')) {
                $params['user'] = $request->query('user');
            }
            $news = api('GET', '/api/v2/user/news/contributes', $params);
            $news->map(function($item){
                $item->collection_count = $item->collections->count();
            });
            $new = clone $news;
            $after = $new->pop()->id ?? 0;
            $data['data'] = $news;
            $html = view('pcview::templates.profile_news', $data, $this->PlusData)->render();

            return response()->json([
                'status' => true,
                'after' => $after,
                'data' => $html
            ]);
        }
        $user = $user->id ? $user : $request->user();
        $user->follower = $user->hasFollower($request->user()->id);
        $type = 0;

        return view('pcview::profile.news', compact('user', 'type'), $this->PlusData);
    }

    /**
     * 收藏的动态
     * @author 28youth
     * @param  Request $request
     * @return mixed
     */
    public function collectFeeds(Request $request)
    {
        $this->PlusData['current'] = 'collect';
        if ($request->isAjax) {
            $params = [
                'offset' => $request->query('offset', 0),
                'limit' => $request->query('limit'),
            ];
            $feeds = api('GET', '/api/v2/feeds/collections', $params);
            $data['feeds'] = $feeds;
            $after = 0;
            $data['conw'] = 815;
            $data['conh'] = 545;
            $html = view('pcview::templates.feeds', $data, $this->PlusData)->render();

            return response()->json([
                'status' => true,
                'after' => $after,
                'data' => $html
            ]);
        }
        $user = $request->user();
        $type = 0;
        $url = route('pc:profilecollectfeeds');

        return view('pcview::profile.collect', compact('user', 'type', 'url'), $this->PlusData);
    }

    /**
     * 收藏的文章
     * @author 28youth
     * @param  Request $request
     * @return mixed
     */
    public function collectNews(Request $request)
    {
        $this->PlusData['current'] = 'collect';
        if ($request->isAjax) {
            $params = [
                'after' => $request->query('after', 0),
                'limit' => $request->query('limit', 10),
            ];
            $news = api('GET', '/api/v2/news/collections', $params);
            $news->map(function($item){
                $item->collection_count = $item->collections->count();
                $item->comment_count = $item->comments->count();
            });
            $new = clone $news;
            $after = $new->pop()->id ?? 0;
            $data['data'] = $news;
            $html = view('pcview::templates.profile_news', $data, $this->PlusData)->render();

            return response()->json([
                'status' => true,
                'after' => $after,
                'data' => $html
            ]);
        }
        $user = $request->user();
        $type = 0;
        $url = route('pc:profilecollectnews');

        return view('pcview::profile.collect', compact('user', 'type', 'url'), $this->PlusData);
    }

    /**
     * 收藏的问答
     * @author 28youth
     * @param  Request $request
     * @return mixed
     */
    public function collectQuestion(Request $request)
    {
        $this->PlusData['current'] = 'collect';
        if ($request->isAjax) {
            $params = [
                'after' => $request->query('after', 0),
                'limit' => $request->query('limit', 10),
            ];
            $answers = api('GET', '/api/v2/user/question-answer/collections', $params);

            $answer = clone $answers;
            $after = $answer->pop()->id ?? 0;
            foreach ($answers as $k => $v) {
                $v->collectible->liked = $v->collectible->liked($this->PlusData['TS']['id']);
                $answers[$k] = $v->collectible;
            }
            $data['datas'] = $answers;
            $html = view('pcview::templates.answer', $data, $this->PlusData)->render();

            return response()->json([
                'status' => true,
                'after' => $after,
                'data' => $html
            ]);
        }
        $user = $request->user();
        $type = 0;
        $url = route('pc:profilecollectqa');

        return view('pcview::profile.collect', compact('user', 'type', 'url'), $this->PlusData);
    }

    /**
     * 收藏的帖子
     * @author ZSYD
     * @param  Request $request
     * @return mixed
     */
    public function collectGroup(Request $request)
    {
        $this->PlusData['current'] = 'collect';
        if ($request->isAjax) {
            $params = [
                'offset' => $request->query('offset', 0),
                'limit' => $request->query('limit', 10),
            ];
            $posts = api('GET', '/api/v2/plus-group/user-post-collections', $params);
            $data['pinneds'] = collect([]);
            $data['posts'] = $posts;
            $after = 0;
            $data['conw'] = 815;
            $data['conh'] = 545;
            $html = view('pcview::templates.group_posts', $data, $this->PlusData)->render();

            return response()->json([
                'status' => true,
                'after' => $after,
                'data' => $html
            ]);
        }
        $user = $request->user();
        $type = 0;
        $url = route('pc:profilecollectgroup');

        return view('pcview::profile.collect', compact('user', 'type', 'url'), $this->PlusData);
    }

    /**
     * 圈子
     * @author 28youth
     * @param  Request     $request
     * @param  int $user_id [用户id]
     * @return mixed
     */
    public function group(Request $request, UserModel $user)
    {
        $this->PlusData['current'] = 'group';
        if ($request->isAjax) {
            $type = (int) $request->query('type');
            $params = [
                'offset' => $request->query('offset', 0),
                'limit' => $request->query('limit', 10),
                'type' => $request->query('type', 'join')
            ];
            if (!$type) {
                $data['type'] = $params['type'];
                $data['group'] = $user->id ? api('GET', '/api/v2/plus-group/groups/users', array_merge($params, ['user_id' => $user->id])) : api('GET', '/api/v2/plus-group/user-groups', $params);
                $html = view('pcview::templates.group', $data, $this->PlusData)->render();
            } else {
                $posts['pinneds'] = collect();
                $posts['posts'] = api('GET', '/api/v2/plus-group/user-group-posts', $params);
                $html = view('pcview::templates.group_posts', $posts, $this->PlusData)->render();
            }

            return response()->json([
                'data' => $html,
            ]);
        }
        $type = 'join';
        $user = $user->id ? $user : $request->user();
        $user->follower = $user->hasFollower($request->user()->id);

        return view('pcview::profile.group', compact('user', 'type'), $this->PlusData);
    }

    /**
     * 问答信息.
     * @author 28youth
     * @param  Request     $request
     * @param  int $user_id [用户id]
     * @return mixed
     */
    public function question(Request $request, UserModel $user)
    {
        $this->PlusData['current'] = 'question';
        if ($request->isAjax) {
            $cate = $request->query('cate', 1);
            switch ($cate) {
                case 1:
                    $params = [
                        'after' => $request->query('after', 0),
                        'type' => $request->query('type', 'all'),
                        'user_id' => $request->query('user_id'),
                    ];
                    $questions = api('GET', '/api/v2/user/questions', $params);
                    $question = clone $questions;
                    $after = $question->pop()->id ?? 0;
                    $data['data'] = $questions;
                    $html = view('pcview::templates.question', $data, $this->PlusData)->render();

                    break;
                case 2:
                    $params = [
                        'after' => $request->query('after', 0),
                        'type' => $request->query('type', 'all'),
                        'user_id' => $request->query('user_id'),
                    ];
                    $answers = api('GET', '/api/v2/user/question-answer', $params);
                    $answer = clone $answers;
                    $after = $answer->pop()->id ?? 0;
                    $data['datas'] = $answers;
                    $html = view('pcview::templates.answer', $data, $this->PlusData)->render();
                    break;
                case 3:
                    $params = [
                        'offset' => $request->query('offset', 0),
                        'limit' => $request->query('limit', 10),
                        'user_id' => $request->query('user_id'),
                    ];
                    $watches = api('GET', '/api/v2/user/question-watches', $params);
                    $data['data'] = $watches;
                    $after = 0;
                    $html = view('pcview::templates.question', $data, $this->PlusData)->render();
                    break;
                case 4:
                    $params = [
                        'after' => $request->query('after', 0),
                        'type' => $request->query('type', 'follow'),
                        'user_id' => $request->query('user_id'),
                    ];
                    $topics = api('GET', '/api/v2/user/question-topics', $params);
                    $topics->map(function($item){
                        $item->has_follow = true;
                    });
                    $topic = clone $topics;
                    $after = $topic->pop()->id ?? 0;
                    $data['data'] = $topics;
                    $html = view('pcview::templates.question_topic', $data, $this->PlusData)->render();
                    break;
            }

            return response()->json([
                'data' => $html,
                'after' => $after
            ]);
        }
        $user = $user->id ? $user : $request->user();
        $user->follower = $user->hasFollower($request->user()->id);

        return view('pcview::profile.question', compact('user'), $this->PlusData);
    }
}
