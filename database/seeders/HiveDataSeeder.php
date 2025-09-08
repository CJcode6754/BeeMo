<?php

class HiveDataSeeder {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "BeeMo_db");

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function run() {
        echo "Clearing old data...\n";
        $this->conn->query("TRUNCATE TABLE aggregated_hive_data");

        echo "Seeding hive data...\n";

        $adminID = 16;
        $hives   = [1, 2];

        $startDate = new DateTime("2024-01-01 00:00:00");
        $endDate   = new DateTime("2025-12-31 23:00:00");

        $interval = new DateInterval("PT1H"); // hourly
        $period   = new DatePeriod($startDate, $interval, $endDate);

        $stmt = $this->conn->prepare("
            INSERT INTO aggregated_hive_data (adminID, hiveID, temperature, humidity, weight, timestamp) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $total = 0;
        foreach ($period as $dt) {
            foreach ($hives as $hiveID) {
                $temperature = rand(300, 370) / 10;     // 30.0 - 37.0 °C
                $humidity    = rand(490, 610) / 10;     // 50% - 90%
                $weight      = rand(1000, 5000);      // 10,000 g - 30,000 g
                $timestamp   = $dt->format("Y-m-d H:i:s");

                $stmt->bind_param("iiddis", $adminID, $hiveID, $temperature, $humidity, $weight, $timestamp);

                if (!$stmt->execute()) {
                    echo "Insert failed: " . $stmt->error . "\n";
                }

                $total++;
                if ($total % 10000 === 0) {
                    echo "$total records inserted...\n";
                }
            }
        }

        echo "Seeding completed! Inserted $total records.\n";
        $stmt->close();
    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
