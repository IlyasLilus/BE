<?php
session_start();
$host = 'localhost';
$db   = 'BE';
$user = 'postgres';
$pass = 'a';
$charset = 'utf8mb4';
$port = '5432';

$dsn = "pgsql:host=$host;dbname=$db;port=$port;user=$user;password=$pass";
$pdo = new PDO($dsn, $user, $pass);

$id_utilisateur = $_SESSION['id_utilisateur'] ?? 'User1';

$query = $pdo->prepare("INSERT INTO Projet (datecreationprojet, pseudonyme) VALUES (now(), :id_utilisateur)");
$query->bindParam(':id_utilisateur', $id_utilisateur);
$query->execute();
