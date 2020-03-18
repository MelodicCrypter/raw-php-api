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
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Instantiate DB
$database = new Database($db_cred);
$db = $database->connect();

// Instantiate blog posts object
$post = new Post($db);

// Get raw data
$data = json_decode(file_get_contents("php://input"));

// Determine which ID to update
$post->id = $data->id;

// Prepare the post
$post->title = $data->title;
$post->body = $data->body;
$post->author = $data->author;
$post->category_id = $data->category_id;

// Update the post
if ($post->update()) {
    echo json_encode(['message' => 'Post Updated']);
} else {
    echo json_encode(['message' => 'Post Not Updated']);
}
