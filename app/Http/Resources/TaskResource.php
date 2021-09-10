<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array|\JsonSerializable|\Illuminate\Contracts\Support\Arrayable
    {
        return [
            'uuid'          => $this->uuid,
            'is_subtask'    => $this->isSubTask(),
            'title'         => $this->title,
            'body'          => $this->body,
            'state'         => StateResource::make($this->whenLoaded('state')),
            'sort_order'    => $this->sort_order,
            'total_subtask' => $this->sub_tasks_count ?? 0,
            'created_at'    => $this->created_at->diffForHumans()
        ];
    }
}
