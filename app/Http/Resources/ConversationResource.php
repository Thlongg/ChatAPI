<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'content'=>$this[0],
            // 'sender'=> [
            //     'id' => $this->user_id,
            //     'name' => $this->name,
            // ],
            // 'created_at' => $this->created_at,
        ];
    }
}
