<?php

class CreateAggregatedHiveDataTable {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "BeeMo_db");
        
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function up() {
        $sql = "CREATE TABLE IF NOT EXISTS aggregated_hive_data (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            adminID INT NOT NULL,
            hiveID INT NOT NULL,
            temperature DECIMAL(5,2),
            humidity DECIMAL(5,2),
            weight DECIMAL(7,2),
            timestamp DATETIME NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_admin_hive (adminID, hiveID),
            INDEX idx_timestamp (timestamp),
            INDEX idx_timestamp_admin_hive (timestamp, adminID, hiveID)
        ) ENGINE=InnoDB";
        
        if (!$this->conn->query($sql)) {
            die("Error creating table: " . $this->conn->error);
        }
        echo "Table created successfully\n";
    }

    public function down() {
        $sql = "DROP TABLE IF EXISTS aggregated_hive_data";
        if (!$this->conn->query($sql)) {
            die("Error dropping table: " . $this->conn->error);
        }
        echo "Table dropped successfully\n";
    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
