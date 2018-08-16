<?php

declare(strict_types=1);

namespace Zhiyi\Plus\API2\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Zhiyi\Plus\Utils\DateTimeToIso8601ZuluString;

class Comment extends JsonResource
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
            'target_user' => $this->target_user,
            'reply_user' => $this->when($this->reply_user, $this->reply_user),
            'body' => $this->body,
            'resourceable' => [
                'type' => $this->commentable_type,
                'id' => $this->commentable_id,
            ],
            'created_at' => $this->dateTimeToIso8601ZuluString(
                $this->{Model::CREATED_AT}
            ),
        ];
    }
}
