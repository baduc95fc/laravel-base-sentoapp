<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'name' => $this->name ?: '',
            'email' => $this->email ?: '',
            'gender' => $this->gender ?: -1,
            'date_of_birth' => $this->date_of_birth ?: '',
            'type' => $this->type,
            'status' => $this->status
        ];
    }
}
