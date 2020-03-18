<?php

namespace SampModels;

use PDO;

class Post
{
    // DB Stuffs
    private object $conn;
    private string $table = 'posts';

    // Post Properties
    public int $id;
    public int $category_id;
    public string $category_name;
    public string $title;
    public string $body;
    public string $author;
    public string $created_at;

    /**
     * Post constructor.
     * @param $db
     */
    public function __construct(object $db)
    {
        $this->conn = $db;
    }

    /**
     * Will get all posts available
     *
     * @return mixed
     */
    public function getAllPosts()
    {
        // Create query
        $query = 'SELECT
                c.name as category_name,
                p.id,
                p.category_id,
                p.title,
                p.body,
                p.author,
                p.created_at
            FROM
                ' . $this->table . ' p
            LEFT JOIN
                categories c ON p.category_id = c.id
            ORDER BY
                p.created_at DESC
        ';

        // Prepared statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    /**
     * Will get only one post available
     */
    public function getOnePost()
    {
        // Create query
        $query = 'SELECT
                c.name as category_name,
                p.id,
                p.category_id,
                p.title,
                p.body,
                p.author,
                p.created_at
            FROM
                ' . $this->table . ' p
            LEFT JOIN
                categories c ON p.category_id = c.id
            WHERE
                p.id = ?
            LIMIT 0,1
        ';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(1, $this->id);

        // Execute
        $stmt->execute();

        // Fetch
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];
        $this->title = $row['title'];
        $this->body = $row['body'];
        $this->author = $row['author'];
    }

    /**
     * Creates a new post
     *
     * @return bool
     */
    public function create()
    {
        $query = 'INSERT INTO ' . $this->table . '
            SET
                title = :title,
                body = :body,
                author = :author,
                category_id = :category_id
        ';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Sanitize data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        // Bind data
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':body', $this->body);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':category_id', $this->category_id);

        // Execute
        if ($stmt->execute()) {
            return true;
        }

        // Print error
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    /**
     * Update specific post
     *
     * @return bool
     */
    public function update()
    {
        $query = 'UPDATE ' . $this->table . '
            SET
                title = :title,
                body = :body,
                author = :author,
                category_id = :category_id
            WHERE
                id = :id
        ';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Basic sanitation data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':body', $this->body);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);

        // Execute
        if ($stmt->execute()) {
            return true;
        }

        // Print error
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    /**
     * Deletes specific post
     *
     * @return bool
     */
    public function delete()
    {
        // Create query
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        // Prepare statement;
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':id', $this->id);

        // Execute
        if ($stmt->execute()) {
            return true;
        }

        // Print error
        printf("Error: %s.\n", $stmt->error);
        return false;
    }
}
