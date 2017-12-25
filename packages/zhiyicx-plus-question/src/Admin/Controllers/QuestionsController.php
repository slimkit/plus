<?php

namespace SlimKit\PlusQuestion\Admin\Controllers;

use Illuminate\Http\Request;
use SlimKit\PlusQuestion\Services\Markdown;
use Zhiyi\Plus\Concerns\FindMarkdownFileTrait;
use SlimKit\PlusQuestion\Models\Topic as TopicModel;
use SlimKit\PlusQuestion\Models\Question as QuestionModel;

class QuestionsController extends Controller
{
    use FindMarkdownFileTrait;

    /**
     * Get questions.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request)
    {
        $limit = (int) $request->query('limit', 15);
        $offset = (int) $request->query('offset', 0);
        $id = $request->query('id');
        $subject = $request->query('subject');
        $user = $request->query('user');
        $topic = $request->query('topic');
        $excellent = $request->query('excellent', false);
        $status = $request->query('status', false);
        $type = (int) $request->query('type', 0);
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $trash = (bool) $request->query('trash', false);

        if ($id) {
            return response()->json([QuestionModel::find($id)], 200);
        }


        $query = QuestionModel::query();
        if ($topic) {
            $topic = TopicModel::find($topic);
            if (! $topic) {
                return response()->json([], 200);
            }

            $query = $topic->questions();
        }

        $query = $query->when($trash, function ($query) {
            return $query->onlyTrashed();
        })
        ->when($subject, function ($query) use ($subject) {
            return $query->where('subject', 'like', sprintf('%%%s%%', $subject));
        })
        ->when($user, function ($query) use ($user) {
            return $query->where('user_id', $user);
        })
        ->when($excellent !== false, function ($query) use ($excellent) {
            return $query->where('excellent', (int) $excellent);
        })
        ->when($status !== false, function ($query) use ($status) {
            return $query->where('answers_count', $status ? '!=' : '=', 0);
        })
        ->when($type === 1, function ($query) {
            return $query->where('automaticity', '!=', 0);
        })
        ->when($type === 2, function ($query) {
            return $query->where('amount', '!=', 0);
        })
        ->when($type === 3, function ($query) {
            return $query->where('amount', '=', 0);
        })
        ->when($startDate, function ($query) use ($startDate) {
            return $query->whereDate('created_at', '>=', $startDate);
        })
        ->when($endDate, function ($query) use ($endDate) {
            return $query->whereDate('created_at', '<=', $endDate);
        });

        $total = $query->count('id');
        $questions = $query->limit($limit)
            ->offset($offset)
            ->orderBy('id', 'desc')
            ->select(['id', 'subject', 'user_id', 'anonymity', 'amount', 'automaticity', 'look', 'excellent', 'status', 'comments_count', 'answers_count', 'watchers_count', 'likes_count', 'views_count', 'created_at', 'updated_at', 'deleted_at'])
            ->get();

        $questions->load(['user', 'topics']);

        return response()->json($questions, 200, [ 'x-total' => $total ]);
    }

    /**
     * Update question.
     *
     * @param \Illuminate\Http\Request $request
     * @param \SlimKit\PlusQuestion\Models\Question $question
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function update(Request $request, Markdown $markdown, QuestionModel $question)
    {
        foreach ($request->only(['subject', 'body', 'excellent']) as $key => $value) {
            $question->$key = $value;
        }
        $question->body = $markdown->safetyMarkdown($question->body);

        $images = $this->findMarkdownImageNotWithModels((string) $question->body);
        $question->getConnection()->transaction(function () use ($question, $images) {
            // Update images.
            $images->each(function ($image) use ($question) {
                $image->channel = 'question:images';
                $image->raw = $question->id;
                $image->save();
            });
            $question->save();
        });

        return response('', 204);
    }

    /**
     * destroy a question.
     *
     * @param \SlimKit\PlusQuestion\Models\Question $question
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function destroy(QuestionModel $question)
    {
        $question->delete();

        return response('', 204);
    }

    /**
     * Restore a question.
     *
     * @param int $question
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function restore(int $question)
    {
        QuestionModel::withTrashed()
            ->where('id', $question)
            ->restore();

        return response('', 204);
    }

    /**
     * Get a question.
     *
     * @param \SlimKit\PlusQuestion\Models\Question $question
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(QuestionModel $question)
    {
        return response()->json($question, 200);
    }
}
