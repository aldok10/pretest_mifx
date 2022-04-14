<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Book extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'isbn',
        'title',
        'description',
        'published_year',
        'avg_review'
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_author');
    }

    public function reviews()
    {
        return $this->hasMany(BookReview::class);
    }

    public function scopeFilter($query, array $filters)
    {
        if (!count($filters)) {
            return $query;
        }

        // Get Valid Sort Column
        $sortColumn = strtolower($filters['sortColumn'] ?? 'id');
        $isValidField = in_array($sortColumn, $this->getFillable());

        // Get Valid Direction Column
        $sortDirection = strtolower($filters['sortDirection'] ?? 'asc');
        $isValidDirection = in_array($sortDirection, ['asc', 'desc']);

        // Set Order Field & Direction
        $orderField = $isValidField ? $sortColumn : 'id';
        $orderDirection = $isValidDirection ? $sortDirection : 'asc';

        // Search Book Data By Filter Paramters
        $query->when($filters['title'] ?? null, function ($query, string $title) {
            $query->where('title', 'like', "%{$title}%");
        })
        ->when( $filters['authors'] ?? null, function ($query, string $authors) {
            // Set Author Ids as Integer Array
            $authorsId = array_map('intval', explode(",", $authors));
            $query->whereHas('authors', function ($query) use ($authorsId) {
                $query->whereIn('id', $authorsId);
            });
        })
        ->leftJoin('book_reviews','book_reviews.book_id','=', 'books.id')
        ->addSelect(DB::raw('AVG(book_reviews.review) as avg_review'), 'books.*')
        ->groupBy('books.id')
        ->orderBy($orderField, $orderDirection);
    }
}
