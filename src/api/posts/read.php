<?php

require_once '../../../vendor/autoload.php';

// Classes
use SampConfig\Database;
use SampModels\Post;

// Enviroment Variables
$dotenv = Dotenv\Dotenv::createImmutable('../../../');
$dotenv->load();
$db_cred = [
    'DB_HOST' => getenv('DB_HOST'),
    'DB_NAME' => getenv('DB_NAME'),
    'DB_USERNAME' => getenv('DB_USERNAME'),
    'DB_PASS' => getenv('DB_PASS'),
];

// Do Authentication first !!!

// Headers
header('Acess-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

// Instantiate DB
$database = new Database($db_cred);
$db = $database->connect();

// Instantiate blog posts object
$post = new Post($db);

// Blog: Get all posts
$result = $post->getAllPosts();
// Get row count
$num = $result->rowCount();

// Check if there are any posts
if ($num > 0) {
    // Post array
    $post_arr = [];
    $post_arr['data'] = [];

    // Loop through result
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        // This is like destructuring in JavaScript
        extract($row);

        $post_item = [
            'id' => $id,
            'title' => $title,
            'body' => html_entity_decode($body),
            'author' => $author,
            'category_id' => $category_id,
            'category_name' => $category_name
        ];

        // Push to "data"
        array_push($post_arr['data'], $post_item);
    }

    // Convert to JSON then response
    echo json_encode($post_arr);
} else {
    // No posts
    echo json_encode(['message' => 'No posts found']);
}
