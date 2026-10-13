<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;

use App\Models\Book;
use Illuminate\Http\Request;


class BookController extends Controller
{
    
    public function findByIsbn(string $isbn): JsonResponse 
    {
        $book = Book::where('isbn', $isbn)->first();

        if ($book === null ) {
           return response()->json([
                'data' => null,
                'error' => [
                    'type' => 'https://bibliotech.fr/book-not-found',
                    'title' => 'Ouvrage introuvable.',
                    'status' => 404,
                    'detail' => 'Aucun ouvrage ne correspond à cet ISBN.',
                ],
                'meta' => null,
            ], 404);
        }
        return response()->json([
            'data' => [
                'book' => [
                    'id' => $book->id,
                    'isbn' => $book->isbn,
                    'title' => $book->title,
                ],
            ],
            'error' => null,
            'meta' => null,
        ], 200);
    }
}
