<?php
try {
    $conn = new PDO("pgsql:host=localhost;port=5432;dbname=postgres;user=postgres;password=a");
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully"; 
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
