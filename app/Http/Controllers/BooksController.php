<?php

namespace App\Http\Controllers;

use App\Models\Books;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\BooksRequest;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\ProductRequest;
use Yajra\DataTables\Facades\DataTables;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('books.index');
    }

    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(BooksRequest $request):JsonResponse
    {
        $data = $request->validated();

        Books::create($data);

        return response()->json([
            'message' => 'Product created successfully.'
        ]);

        
    }

    /**
     * Display the specified resource.
     */

    public function show(string $id): JsonResponse
    {
        return response()->json([
            'data' => Books::find($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(BooksRequest $request, Books $product)
    {
        $book = $request->validated();
        $book['slug'] = Str::slug($book['name']);
        
    
        $product->update($book);
    
        return response()->json([
            'message' => 'Product updated successfully.'
        ]);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Books $book)
    {
        $book->delete();
    
        return response()->json([
            'message' => 'book deleted successfully.'
        ]);
    }
    
    public function dataTable(Request $request)
    {
        $book = Books::query(); // Lebih baik pakai query() daripada get()

        return DataTables::of($book)
            ->addIndexColumn()
            ->addColumn('action', function ($book) {
                return 
                "<div class='btn-group'>
                    <button type='button' class='btn btn-success btn-edit' onclick='editModal(this)' data-id='" . $book->id . "'>Edit</button>
                    <button type='button' class='btn btn-danger btn-delete' onclick='deleteModal(this)' data-id='" . $book->id . "'>Delete</button>
                </div>";
            })
            
            
            ->make(true);
    }

    public function fetchFromGoogleBooks(Request $request)
    {
        $query = $request->input('query', 'programming'); // Default query is now 'programming'
        $apiKey = env('GOOGLE_BOOKS_API_KEY'); // Store your API key in the .env file
    
        $response = Http::get("https://www.googleapis.com/books/v1/volumes", [
            'q' => $query,
            'key' => $apiKey,
        ]);
    
        if ($response->successful()) {
            $booksData = $response->json()['items'] ?? [];
    
            foreach ($booksData as $book) {
                $volumeInfo = $book['volumeInfo'] ?? [];
    
                Books::updateOrCreate(
                    ['slug' => \Str::slug($volumeInfo['title'] ?? 'unknown')],
                    [
                        'name' => $volumeInfo['title'] ?? 'Unknown Title',
                        'author' => $volumeInfo['authors'][0] ?? 'Unknown Author',
                        'description' => $volumeInfo['description'] ?? 'No Description',
                        'published_date' => $volumeInfo['publishedDate'] ?? null,
                    ]
                );
            }
    
            return response()->json(['message' => 'Books fetched and stored successfully.']);
        }
    
        return response()->json(['message' => 'Failed to fetch books from Google Books API.'], 500);
    }
}
