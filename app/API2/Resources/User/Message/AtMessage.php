<?php

declare(strict_types=1);

namespace Zhiyi\Plus\API2\Resources\User\Message;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Zhiyi\Plus\Utils\DateTimeToIso8601ZuluString;

class AtMessage extends JsonResource
{
    use DateTimeToIso8601ZuluString;

    /**
     * The resource to array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'resourceable' => [
                'type' => $this->resourceable_type,
                'id' => $this->resourceable_id,
            ],
            $this->mergeWhen($request->query('load'), [
                'resource' => ['暂不提供数据支持']
            ]),
            'created_at' => $this->dateTimeToIso8601ZuluString(
                $this->{Model::CREATED_AT}
            ),
        ];
    }
}
