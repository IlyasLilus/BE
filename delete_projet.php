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

$idProjet = $_POST['idProjet'];
echo $idProjet;
$query = $pdo->prepare("DELETE FROM Projet WHERE idProjet = :idProjet");
$query->bindParam(':idProjet', $idProjet, PDO::PARAM_INT);
$query->execute();
