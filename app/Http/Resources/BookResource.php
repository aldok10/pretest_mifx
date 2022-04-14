<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AuthorResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            // @TODO implement
            //JSON Resource Book into array
            'id' => $this->id,
            'isbn' => $this->isbn,
            'title' => $this->title,
            'description' => $this->description,
            'published_year' => $this->published_year,
            'authors' => AuthorResource::collection($this->authors),
            'review' => [
                'avg' => round($this->reviews->avg('review')),
                'count' => $this->reviews->count()
            ]
        ];
    }
}
