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

// Get ID
$post->id = isset($_GET['id']) ? $_GET['id'] : die();

// Get single posts
$post->getOnePost();

// Create an array based from the query
$singlePost = [
    'id' => $post->id,
    'title' => $post->title,
    'body' => $post->body,
    'author' => $post->author,
    'category_id' => $post->category_id,
    'category_name' => $post->category_name,
];

// Convert to JSON then response
print_r(json_encode($singlePost));
