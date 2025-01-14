<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Remove bullet points from the <ul> */
        ul {
            list-style-type: none;
            padding-left: 0;
        }

        .post-item {
            margin-bottom: 20px; /* Space between posts */
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .post-title {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .post-body {
            font-size: 1rem;
            color: #555;
        }

        .post-id, .post-userId {
            font-size: 0.9rem;
            color: #777;
        }
    </style>
</head>
<body class="container mt-5">
    <h2 class="text-center mb-2">Posts:</h2>
    
    <!-- Posts List -->
    <ul>
        @foreach ($paginatedPosts as $post) <!-- Use $paginatedPosts here -->
            <li class="post-item">
            
                <p class="post-title text-center">{{ $post['title'] }}</p>
                <p class="post-body">{{ $post['body'] }}</p>
                <p class="post-id">Post ID: {{ $post['id'] }}</p>
            </li>
        @endforeach
    </ul>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center my-4 mx-4">
    {{ $paginatedPosts->links('pagination::bootstrap-5') }} <!-- Use $paginatedPosts here -->
       </div>


    <!-- Bootstrap 5 JS and Popper.js CDN (optional, for Bootstrap components) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
