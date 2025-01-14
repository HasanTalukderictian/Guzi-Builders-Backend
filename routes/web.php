<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

/*
|---------------------------------------------------------------------------
| Web Routes
|---------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::get('/admin', function () {
    return view('admin');
});

Route::get('/admin/home', function () {
    return view('home');
})->name('admin.home');

Route::get('/index', function () {
    // Fetch the posts data from the API
    $response = Http::get('https://jsonplaceholder.typicode.com/posts');

    // Decode the JSON response into an array
    $posts = collect($response->json());

    // Set the current page
    $currentPage = LengthAwarePaginator::resolveCurrentPage();

    // Slice the collection to get the posts for the current page (10 posts per page)
    $perPage = 10;
    $currentItems = $posts->slice(($currentPage - 1) * $perPage, $perPage)->all();

    // Create a LengthAwarePaginator instance
    $paginatedPosts = new LengthAwarePaginator(
        $currentItems,  // Current page items
        $posts->count(), // Total number of posts
        $perPage,  // Number of items per page
        $currentPage,  // Current page
        ['path' => LengthAwarePaginator::resolveCurrentPath()]  // Path for pagination links
    );

    // Pass the paginated data to the Blade view
    return view('index', compact('paginatedPosts'));
});
