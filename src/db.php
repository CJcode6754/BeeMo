<?php
class Database {
    // private $host = 'localhost';
    // private $username = 'u497761604_BeeMo';
    // private $password = 'NewPassword@6789054321';
    // private $dbname = 'u497761604_BeeMo_db';

    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbname = 'BeeMo_db';

    private $conn;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function closeConnection() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
