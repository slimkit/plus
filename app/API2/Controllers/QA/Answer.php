<?php

declare(srrict_types=1);

namespace Zhiyi\Plus\API2\Controllers\QA;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Zhiyi\Plus\API2\Controllers\Controller;
use SlimKit\PlusQuestion\Models\Answer as AnswerModel;
use function Zhiyi\Plus\filterUrlStringLength;

class Answer extends Controller
{
    public function repostedAnswerList(Request $request, AnswerModel $model): JsonResponse
    {
        $id = array_values(
            array_filter(
                explode(',', $request->query('id', ''))
            )
        );

        if (empty($id)) {
            return new JsonResponse([], JsonResponse::HTTP_OK);
        }

        $answers = $model
            ->query()
            ->with(['question'])
            ->whereIn('id', $id)
            ->get()
            ->map(function (AnswerModel $answer) {
                return [
                    'id' => $answer->id,
                    'body' => filterUrlStringLength($answer->body, 100),
                    'question' => ! $answer->question ? null : [
                        'id' => $answer->question->id,
                        'subject' => $answer->question->subject,
                    ],
                ];
            });
        
        return new JsonResponse($answers, JsonResponse::HTTP_OK);
    }
}
