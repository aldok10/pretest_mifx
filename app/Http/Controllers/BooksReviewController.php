<?php

namespace App\Http\Controllers;

use App\BookReview;
use App\Http\Requests\PostBookReviewRequest;
use App\Http\Resources\BookReviewResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Book;

class BooksReviewController extends Controller
{
    public function __construct()
    {

    }

    public function store(PostBookReviewRequest $request, Book $book)
    {
        // @TODO implement
        // Insert into table book_review
        $data = $request->validated();
        $data['book_id'] = $book->id;
        $data['user_id'] = auth()->user()->id;

        $bookReview = BookReview::create($data);

        return new BookReviewResource($bookReview);
    }

    public function destroy(Book $book, BookReview $review, Request $request)
    {
        // @TODO implement
        //Delete data from Table book_review
        $review->delete();
        return response()->json([], 204);
    }
}
