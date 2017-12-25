<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use SlimKit\PlusQuestion\Models\Topic as TopicModel;
use SlimKit\PlusQuestion\Models\TopicApplication as TopicApplicationModel;
use SlimKit\PlusQuestion\API2\Requests\TopicApplication as TopicApplicationRequest;

class TopicApplicationController extends Controller
{
    /**
     * @author bs<414606094@qq.com>
     * @param  SlimKit\PlusQuestion\API2\Requests\TopicApplication $request
     * @param  SlimKit\PlusQuestion\Models\TopicApplication $topicApplicationModel
     * @param  SlimKit\PlusQuestion\Models\Topic $topicModel
     * @return mixed
     */
    public function store(TopicApplicationRequest $request, TopicApplicationModel $topicApplicationModel, TopicModel $topicModel)
    {
        $user = $request->user();
        $name = $request->input('name');
        $description = $request->input('description');
        if ($topicModel->where('name', '=', $name)->first() || $topicApplicationModel->where('name', '=', $name)->first()) {
            return response()->json(['message' => [trans('plus-question::topics.applied')]], 422);
        }

        $topicApplicationModel->user_id = $user->id;
        $topicApplicationModel->name = $name;
        $topicApplicationModel->description = $description;
        $topicApplicationModel->save();

        return response()->json([
            'message' => [trans('plus-question::messages.success')],
        ], 201);
    }
}
