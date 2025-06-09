<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\v1\UserResource;
use App\Http\Resources\v1\MessageResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'participants' => UserResource::collection($this->whenLoaded('users')),
            'latest_message' => $this->whenLoaded('latestMessage', fn () => new MessageResource($this->latestMessage)),
        ];
    }
}
