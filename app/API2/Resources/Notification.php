<?php

declare(strict_types=1);

namespace Zhiyi\Plus\API2\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Zhiyi\Plus\Utils\DateTimeToIso8601ZuluString;

class Notification extends JsonResource
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
            'created_at' => $this->dateTimeToIso8601ZuluString($this->created_at),
            'read_at' => $this->dateTimeToIso8601ZuluString($this->read_at),
            'data' => $this->data,
        ];
    }
}
