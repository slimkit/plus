<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\User as UserModel;
use function zhiyi\Component\ZhiyiPlus\PlusComponentPc\replaceUrl;
use function zhiyi\Component\ZhiyiPlus\PlusComponentPc\getUserInfo;
use function zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatRepostable;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\api;
use function Zhiyi\Plus\username;

class ProfileController extends BaseController
{
    /**
     * 动态
     * @author Foreach
     * @param  Request     $request
     * @param  string      $user [用户id 或用户名]
     * @return mixed
     */
    public function feeds(Request $request, ?string $user = null)
    {
        if (! $user) {
            $user = $request->user();
        } else {
            $user = UserModel::where(username($user), $user)->with('tags')->first();
        }
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
                    $feeds['feeds'] = formatRepostable($feeds['feeds']);
                    $after = last($feeds['feeds'])['id'] ?? 0;
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
        if ($user) {
            $user->follower = $user->hasFollower($request->user()->id);
            $data['user'] = $user->toArray();
        } else {
            return abort(404);
        }

        return view('pcview::profile.index', $data, $this->PlusData);
    }

    /**
     * 文章.
     * @author 28youth
     * @param  Request $request
     * @param  string  $user [用户id 或用户名]
     * @return mixed
     */
    public function news(Request $request, ?string $user = null)
    {
        $this->PlusData['current'] = 'news';
        if (! $user) {
            $user = $request->user();
        } else {
            $user = UserModel::where(username($user), $user)->with('tags')->first();
        }
        if ($request->isAjax) {
            $params = [
                'type' => $request->query('type'),
                'after' => $request->query('after', 0)
            ];
            if ($request->query('user')) {
                $params['user'] = $request->query('user');
            }
            $news = api('GET', '/api/v2/user/news/contributes', $params);
            $after = last($news)['id'] ?? 0;
            $data['data'] = $news;
            $html = view('pcview::templates.profile_news', $data, $this->PlusData)->render();

            return response()->json([
                'status' => true,
                'after' => $after,
                'data' => $html
            ]);
        }
        $user->follower = $user->hasFollower($request->user()->id);
        $data['user'] = $user->toArray();
        $data['type'] = 0;

        return view('pcview::profile.news', $data, $this->PlusData);
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
            $data['feeds'] = formatRepostable($feeds);
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
            $after = last($news)['id'] ?? 0;
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

            $after = last($answers)['id'] ?? 0;
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
    public function group(Request $request, ?string $user)
    {
        if (! $user) {
            $user = $request->user();
        } else {
            $user = UserModel::where(username($user), $user)->with('tags')->first();
        }
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
                $data['group'] = $user->id ? api('GET', '/api/v2/plus-group/user-groups', $params) : api('GET', '/api/v2/plus-group/groups/users', array_merge($params, ['user_id' => $user->id]));
                $html = view('pcview::templates.group', $data, $this->PlusData)->render();
            } else {
                $posts['posts'] = api('GET', '/api/v2/plus-group/user-group-posts', $params);
                $html = view('pcview::templates.group_posts', $posts, $this->PlusData)->render();
            }

            return response()->json([
                'data' => $html,
            ]);
        }
        $user->follower = $user->hasFollower($request->user()->id);
        $data['user'] = $user->toArray();
        $data['type'] = 'join';

        return view('pcview::profile.group', $data, $this->PlusData);
    }

    /**
     * 问答信息.
     * @author 28youth
     * @param  Request     $request
     * @param  int $user_id [用户id]
     * @return mixed
     */
    public function question(Request $request, ?string $user)
    {
        if (! $user) {
            $user = $request->user();
        } else {
            $user = UserModel::where(username($user), $user)->with('tags')->first();
        }
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
                    $after = last($questions)['id'] ?? 0;
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
                    $after = last($answers)['id'] ?? 0;
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
                    foreach ($topics as $key => &$value) {
                        $value['has_follow'] = true;
                    }
                    $after = last($topics)['id'] ?? 0;
                    $data['data'] = $topics;
                    $html = view('pcview::templates.question_topic', $data, $this->PlusData)->render();
                    break;
            }

            return response()->json([
                'data' => $html,
                'after' => $after
            ]);
        }
        $user->follower = $user->hasFollower($request->user()->id);
        $data['user'] = $user->toArray();

        return view('pcview::profile.question', $data, $this->PlusData);
    }
}
