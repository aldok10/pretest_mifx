<?php

namespace App\Http\Controllers;

use App\Book;
use App\Author;
use App\Http\Requests\PostBookRequest;
use App\Http\Resources\BookResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $book = Book::create($data);

            // Make Sure The Author ID Values Is Really Integer Type
            $authors = array_map('intval', $data['authors']);
            $book->authors()->sync($authors);

            DB::commit();

            return new BookResource($book);

        } catch (\Throwable $th) {
            // Fixing Issue When Insert Data with Rollback
            DB::rollBack();
            return response([
                // "error" => $th->getMessage() // Enable On Development
                "error" => "Opps! Something went wrong"
            ], 500);
        }

    }
}
