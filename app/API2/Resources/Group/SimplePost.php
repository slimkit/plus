<?php

declare(strict_types=1);

namespace Zhiyi\Plus\API2\Resources\Group;

use Illuminate\Http\Resources\MissingValue;
use Illuminate\Http\Resources\Json\JsonResource;

class SimplePost extends JsonResource
{
    /**
     * Transform post resource to array.
     * @param \Illuminate\Http\Requests $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'group_id' => $this->group_id,
            'title' => $this->title,
            'summary' => $this->summary,
            'image' => $this->whenLoaded('images', function () {
                return $this->images->first()->id ?? new MissingValue();
            }),
        ];
    }
}
