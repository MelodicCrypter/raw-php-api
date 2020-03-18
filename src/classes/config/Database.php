<?php

namespace SampConfig;

use PDO;

class Database
{
    // DB Parameters
    private string $db_host;
    private string $db_name;
    private string $db_username;
    private string $db_pass;
    private $conn;

    /**
     * Database constructor.
     * The db_cred is an array created from dotenv variables
     *
     * @param array $db_cred
     */
    public function __construct(array $db_cred)
    {
        $this->db_host = $db_cred['DB_HOST'];
        $this->db_name = $db_cred['DB_NAME'];
        $this->db_username = $db_cred['DB_USERNAME'];
        $this->db_pass = $db_cred['DB_PASS'];
    }

    /**
     * This will connect the app to the database
     *
     * @return PDO|null
     */
    public function connect()
    {
        $this->conn = null;

        // PDO
        try {
            $this->conn = new PDO('mysql:host=' . $this->db_host . ';dbname=' . $this->db_name, $this->db_username, $this->db_pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }

        // Return the connection
        return $this->conn;
    }
}
