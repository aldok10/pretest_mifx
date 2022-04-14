<?php

namespace App\Http\Controllers;

use App\Book;
use App\Author;
use App\Http\Requests\PostBookRequest;
use App\Http\Resources\BookResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function __construct()
    {
        // $this->model = new Book();
    }

    public function index(Request $request)
    {
        // @TODO implement
        // Index
        $validRequests = $request->only('page', 'sortColumn','sortDirection','title','authors');

        $data = Book::with('authors')
        ->filter($validRequests)
        ->paginate(15);

        return BookResource::collection($data);
    }

    public function store(PostBookRequest $request)
    {
        // @TODO implement
        //Insert into table books
        $data = $request->validated();
        $book = Book::create($data);

        $authors = [];
        foreach ($data['authors'] as $key => $value) {
            $authors[] = Author::find($value);
        }

        $book->authors()->saveMany($authors);

        return new BookResource($book);

    }
}
